<?php

namespace Shopbox\Http\Controllers\Auth;

use Carbon\Carbon;
use Chumper\Zipper\Zipper;
use Jenssegers\Agent\Agent;
use Shopbox\Models\Zpanel\Track;
use Shopbox\Models\Front\Theme;
use Shopbox\Models\Front\StoreTheme;
use Shopbox\Models\Zpanel\User;
use Shopbox\Models\Zpanel\RestrictedDomain;
use Shopbox\Models\Zpanel\Plugin;
use Shopbox\Models\Zpanel\Plan;
use Shopbox\Models\Zpanel\Store;
use Shopbox\Models\Zpanel\StoreSetting;
use Shopbox\Models\Zpanel\StorePayment;
use Shopbox\Models\Zpanel\Country;
use Shopbox\Models\Zpanel\State;
use Shopbox\Models\Zpanel\Timezone;
use Shopbox\Models\Zpanel\Currency;
use Shopbox\Models\Zpanel\Client;
use Modules\Product\Entities\Category;
use Modules\Product\Entities\TaxClass;
use Modules\Product\Entities\StoreLocation;
use Modules\Product\Entities\Attribute;
use Modules\Product\Entities\OptionSet;
use Modules\Product\Entities\Option;
use Modules\Page\Entities\Page;
use Modules\Menu\Entities\Menu;
use Modules\Menu\Entities\Item;
use Modules\ShopboxPay\Entities\Config;
use Modules\EmailTemplate\Entities\Email;
use Shopbox\Events\Auth\UserSignedUp;
use Shopbox\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Shopbox\Traits\RegisterStore;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;

class AdminRegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers, RegisterStore;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/merchant/login';
    protected $geoip;
    protected $imageManager;
    protected $agent;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:admin');
        $this->geoip = geoip()->getLocation(geoip()->getClientIP());
        $this->agent = new Agent();

    }


    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        $countries = Country::where('status', 1)->orderBy('name', 'asc')->get();
        $states = State::where('status', 1)->get();
        $location = $this->geoip;
     
        return view('auth.register', compact('countries', 'states'));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'store_name' => 'required|string|max:255',
            'domain' => 'required|string|max:255|unique:stores|unique_domain:restricted_domains,word',
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|numeric|min:8|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'country' => 'required|numeric',
            'city' => 'required|max:255',
            'state' => 'nullable|numeric|required_state:id,'.$data['country'],
            'postal_code' => 'required|max:255',
            'address1' => 'required|max:255',
            'address2' => 'max:255',
        ], [
            'unique_domain' => 'Domain not available.'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \Shopbox\User
     */
    protected function create(array $data)
    {   
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'active' => 0,
            'master' => 1
        ]);

        $store = new Store;
        $store->id = generateStoreID($store, 1);
        $store->plan()->associate(Plan::where('slug', 'trial')->first());
        $store->store_name = $data['store_name'];
        $store->domain = str_slug($data['domain'], '');
        $store->store_email = $data['email'];
        $store->trans_email = $data['email'];
        $store->country()->associate($data['country']);
        $store->address1 = $data['address1'];

        if($data['address2']) {
          $store->address2 = $data['address2'];
        }
        
        $store->postcode = $data['postal_code'];
        $store->city =  $data['city'];

        if($data['state']) {
          $store->state()->associate($data['state']); 
        }
       
        $store->expiry_date = Carbon::now()->addDays(14);
        $store->save();
        $store->users()->attach($user->id);

        $this->saveStoreSetting($store);
        $this->storeClient($store);

        $this->saveDefaultCategory($store);
        $this->saveDefaultTaxClass($store);
        $this->applyDefaultTheme($store);
        $this->saveStorePayments($store);
        $this->saveDefaultMenu($store);
        $this->saveDefaultPage($store);
        $this->saveEmailTemplates($store);
        $this->saveDefaultStoreLocation($store, $user);
        $this->saveDefaultOptionSets($store);
        $this->copyDefaultFolders($store); 

		if(request()->hasCookie('source')) {
            $this->saveReferral($store, request()->cookie('source'));
            Cookie::queue(Cookie::forget('source', '/'));
        }		

        return $user;
    }

    
    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        $this->guard()->logout();

        event(new UserSignedUp($user));

        //return redirect($this->redirectPath())->withSuccess('Please check your email for an activation link.');
        return response()->json([
          'redirect_to' => url($this->redirectPath().'?user='.$user->email)
        ]);
    }

}
