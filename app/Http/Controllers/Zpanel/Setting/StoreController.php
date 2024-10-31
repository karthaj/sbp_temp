<?php

namespace Shopbox\Http\Controllers\Zpanel\Setting;

use Illuminate\Http\Request;
use Shopbox\Http\Requests\Store\ProfileRequest;
use Shopbox\Http\Controllers\Controller;
use Intervention\Image\ImageManager;
use Shopbox\Services\Geoplugin\Contracts\GeoPluginContract;
use Shopbox\Models\Zpanel\Country;
use Shopbox\Models\Zpanel\Timezone;
use Shopbox\Models\Zpanel\WeightUnit;
use Shopbox\Models\Zpanel\Currency;
use Shopbox\Models\Zpanel\Store;
use Shopbox\Models\Zpanel\StoreSetting;
use Shopbox\Services\GeoPlugin;

class StoreController extends Controller
{
    protected $imageManager;

    public function __construct(ImageManager $imageManager)     
    { 

        $this->imageManager = $imageManager;
    }

    public function edit(Request $request)
    {     
    	$countries = Country::where('status', 1)->orderBy('name', 'asc')->get();
    	$timezones = Timezone::orderBy('timezone', 'asc')->get();
    	$units = WeightUnit::where('status', 1)->get();
    	$currencies = Currency::where('status', 1)->get();
    	$store = Store::find(session('tenant'));
        $action = route('settings.update');

        if($request->redirect) {
            $action = route('settings.update', 'redirect='.request()->redirect);
        }
    	
    	return view('zpanel.settings.general.edit', compact('countries', 'timezones', 'units', 'currencies', 'store', 'action'));
    }

    public function update(profileRequest $request)
    {
        $store = $request->tenant();
        $store->store_name = $request->store_name;
        $store->store_email = $request->account_email;
        $store->trans_email = $request->customer_email;
        $store->company = $request->company;
        $store->phone = $request->phone;
        $store->address1 = $request->address1;
        $store->address2 = $request->address2;
        $store->city = $request->city;
        $store->postcode = $request->zip_code;
        $store->country()->associate($request->country);
        if(!empty($request->state)) {
            $store->state()->associate($request->state);
        }

        $store->save();

        $store->setting->timezone()->associate($request->timezone);
        $store->setting->currency()->associate($request->store_currency);
        $store->setting->weight()->associate($request->weight_unit);
        $store->setting->order_id_prefix = $request->order_prefix;
        $store->setting->order_id_suffix = $request->order_suffix;

        $path = public_path('stores').'/'.$request->tenant()->domain.'/img/';

        if($request->file('logo')) {
            $uploadedFile = $request->file('logo');
            $name = str_slug('storelogo_'.$store->domain, '_');
            $this->imageManager->make($uploadedFile->getPathName())
            ->resize(1200, 630, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
             })
            ->save($path.$image_path = $name.'.'.$uploadedFile->getClientOriginalExtension());
            $store->setting->logo = $image_path;
        }

        if($request->file('favicon')) {
            $uploadedFile = $request->file('favicon');
            $processedImage = $this->imageManager->make($uploadedFile->getPathName())
                                 ->fit(32, 32, function ($constraint) {
                                    $constraint->upsize();
                                 })
                                ->save($path.$image_path = $store->domain.'.'.$uploadedFile->getClientOriginalExtension());

            $store->setting->favicon = $processedImage->basename;
        }

        if($request->enable_returns) {
            $store->setting->enable_returns = $request->enable_returns;
        } else {
            $store->setting->enable_returns = 0;
        }

        if($request->partial_returns) {
            $store->setting->enable_partial_returns = $request->partial_returns;
        } else {
            $store->setting->enable_partial_returns = 0;
        }

        $store->setting->save();

        if(!$request->redirect) {
             return redirect()->back()->withSuccess('Store profile updated successfully.');
        }
       
        return redirect($request->redirect);

    }

    public function index()
    {
        return view('zpanel.settings.index');
    }

    public function getCountryStates(Country $country)
    {
        $states = getStates($country);

        $elem = '<option value="">None</option>';

        if($states->count()) {
            foreach ($states as $state) {
                $elem .= '<option value="'.$state->id.'">'.$state->name.'</option>';
            }
        }

        return response()->json([
            'states' => $elem
        ]);
    }


}
