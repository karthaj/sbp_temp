<?php

namespace Shopbox\Http\Controllers\Front\Customer;

use Illuminate\Http\Request;
use Shopbox\Models\Zpanel\Store;
use Modules\Order\Entities\Order;
use Shopbox\Models\Zpanel\Country;
use Intervention\Image\ImageManager;
use Modules\Customer\Entities\Address;
use Shopbox\Http\Controllers\Controller;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Shopbox\Http\Requests\StoreFront\Account\AvatarFormRequest;
use Shopbox\Http\Requests\StoreFront\Account\AccountFormRequest;
use Shopbox\Http\Requests\StoreFront\Account\AddressFormRequest;
use Shopbox\Transformers\StoreFront\ProductCollectionTransformer;

class AccountController extends Controller
{
    protected $imageManager;

    public function __construct(ImageManager $imageManager)     
    { 

        $this->imageManager = $imageManager;
    }

    public function index(Request $request)
    {
        $awaiting_delivery = 0;

        if(auth()->user()->orders()->where('store_id', session('store')->id)->count()) {
            $orders = auth()->user()->orders()->with(['state'])->where('store_id', session('store')->id)->get();
            foreach($orders as $order) {
                if($order->state->slug !== 'refunded' && $order->state->slug !== 'completed' && $order->state->slug !== 'cancelled') {
                    $awaiting_delivery += 1;
                }
            }
        }
  
        if($request->tab === 'add_address') {

            $countries = Country::orderBy('name', 'asc')->get();
            return view($request->viewPath.'.account', compact('countries', 'awaiting_delivery'));

        } else if($request->tab === 'view_order' || $request->tab === 'return') {

            $order = $request->user()->orders()->where('store_id', session('store')->id)->where('id', $request->order_id)->first();
            if(!$order)
                abort(404);
            return view($request->viewPath.'.account', compact('order', 'awaiting_delivery'));

        } else if($request->tab === 'wishlist') {

            $wishlists = auth()->user()->wishlists()->where('store_id', session('store')->id)->paginate(15);

            $products = fractal()
                        ->collection($wishlists->getCollection())
                        ->transformWith(new ProductCollectionTransformer)
                        ->paginateWith(new IlluminatePaginatorAdapter($wishlists))
                        ->toArray();
           
            return view($request->viewPath.'.account', compact('awaiting_delivery', 'products'));
        }
       
        return view($request->viewPath.'.account', compact('awaiting_delivery'));
    }

    public function store(AddressFormRequest $request)
    {
        $address = new Address;
        $address->customer()->associate($request->user());
        $address->country()->associate($request->country);

        if($request->state)
            $address->state()->associate($request->state);

        $address->alias = $request->alias;
        $address->firstname = $request->firstname;
        $address->lastname = $request->lastname;
        $address->address = $request->address1;
        $address->address2 = $request->address2;
        $address->zip_code = $request->zipcode;
        $address->city = $request->city;
        $address->company = $request->company;
        $address->phone = $request->phone;
        $address->save();

        return redirect(getStoreUrl(session('store')).'/account?tab=address_list');

    }


    public function show(Request $request, Order $order)
    {
        return view($request->viewPath.'.order', compact('order'));
    }

    public function update(AccountFormRequest $request)
    {
        $request->user()->update([
            'firstname'=> $request->firstname,
            'lastname'=> $request->lastname,
            'company'=> $request->company,
            'phone' => $request->phone
        ]);

        return redirect()->back()->withSuccess('Your account details have been updated.');
    }

    public function destroy(Address $address)
    {
        $address->delete();

        return redirect()->back();
    }

    public function avatar(AvatarFormRequest $request)
    {
        $uploadedFile = $request->file('avatar');
        $directory = createDirectory(public_path('profiles/'.auth()->user()->id));
        $name = str_slug(pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME), '-');
        $extension = $uploadedFile->getClientOriginalExtension();

        $this->imageManager->make($uploadedFile->getPathName())
                        ->resize(100, 100, function ($constraint) {
                            $constraint->aspectRatio();
                         })
                        ->save($directory.'/'.$name.'.'.$extension);

        auth()->user()->update([
            'avatar' => $name.'.'.$extension
        ]);

        return redirect()->back();
    }

    public function unsubscribe(Store $store)
    {
        if(!auth()->user()->stores->contains('id', $store->id)) {
            return redirect()->back();
        }

        $store->customers()->detach(auth()->user()->id);

        return redirect()->back()->withSuccess('Your have successfully unsubscribed from <span class="font-weight-bold">'.$store->store_name.'</span> store.');
    }

    public function agreement()
    {
        if(auth()->user()->stores->contains('id', session('store')->id)) {
            return redirect()->route('customer.profile');
        }

        return view('agreement');
    }

    public function accept(Request $request)
    {
        $this->validate($request, [
            'agree' => 'required|boolean'
        ]);

        auth()->user()->stores()->syncWithoutDetaching(session('store')->id, ['created_at_tz' => auth()->user()->freshTimestamp()->timezone(session('store')->setting->timezone->timezone)]);

        return redirect()->route('customer.profile');
    }

}
