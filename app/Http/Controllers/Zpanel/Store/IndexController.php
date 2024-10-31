<?php

namespace Shopbox\Http\Controllers\Zpanel\Store;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Shopbox\Models\Zpanel\Plan;
use Shopbox\Models\Zpanel\Store;
use Shopbox\Traits\RegisterStore;
use Shopbox\Models\Zpanel\Country;
use Illuminate\Support\Facades\Mail;
use Shopbox\Mail\Onboard\WelcomeEmail;
use Shopbox\Http\Controllers\Controller;
use Shopbox\Http\Requests\Store\StoreFormRequest;

class IndexController extends Controller
{
	use RegisterStore;

	protected $geoip;

	public function __construct()
	{
		$this->geoip = geoip()->getLocation(request()->ip());
	}

    public function create()
    {
    	$countries = Country::orderBy('name', 'asc')->get();

    	return view('zpanel.stores.create', compact('countries'));
    }

    public function store(StoreFormRequest $request)
    {
    	$store = new Store;
        $store->id = generateStoreID($store, 1);
        $store->plan()->associate(Plan::where('slug', 'trial')->first());
        $store->store_name = $request->store_name;
        $store->domain = str_slug($request->domain, '');
        $store->store_email = auth()->user()->email;
        $store->trans_email = auth()->user()->email;
        $store->country()->associate($request->country);
        $store->address1 = $request->address1;

        if($request->address2) {
          $store->address2 = $request->address2;
        }
        
        $store->postcode = $request->postal_code;
        $store->city =  $request->city;

        if($request->state) {
          $store->state()->associate($request->state); 
        }
       
        $store->expiry_date = Carbon::now()->addDays(14);
        $store->save();
        $store->users()->attach(auth()->user()->id);

        $this->saveStoreSetting($store);
        $this->storeClient($store);
        $this->saveDefaultCategory($store);
        $this->saveDefaultTaxClass($store);
        $this->applyDefaultTheme($store);
        $this->saveStorePayments($store);
        $this->saveDefaultMenu($store);
        $this->saveDefaultPage($store);
        $this->saveEmailTemplates($store);
        $this->saveDefaultStoreLocation($store, auth()->user());
        $this->saveDefaultOptionSets($store);
        $this->copyDefaultFolders($store);

        Mail::to(auth()->user())->queue(new WelcomeEmail($store));
        return redirect(config('app.url').'/merchant/manage/store')->withSuccess('Store created successfully.');
    }

}
