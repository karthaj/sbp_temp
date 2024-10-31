<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Shopbox\Tenant\Manager;
use Shopbox\Models\Zpanel\Plugin;
use Shopbox\Models\Zpanel\Country;
use Shopbox\Models\Zpanel\Store;
use Modules\Product\Entities\ShippingMethod;
use Modules\Product\Entities\ShippingZone;
use Modules\Product\Entities\ShippingZoneLocation;
use Modules\Product\Entities\ShippingZoneMethod;
use Modules\Product\Transformers\ShippingZoneMethodTransformer;
use Modules\Product\Http\Requests\Shipping\StorePickupFormRequest;
use Modules\Product\Http\Requests\Shipping\ShippingZoneFormRequest;

class ShippingController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index(Request $request)
    {
        // $params = [
        //     'ClientInfo' => [
        //         'AccountCountryCode'    => 'LK',
        //         'AccountEntity'         => 'CMB',
        //         'AccountNumber'         => '116853',
        //         'AccountPin'            => '332432',
        //         'UserName'              => 'farthab@nastars.lk',
        //         'Password'              => 'Abcd@1234',
        //         'Version'               => 'v1.0'
        //     ],
        //     'Transaction' => [
        //         'Reference1'           => '001' 
        //     ],
        //     'OriginAddress' => [
        //         'PostCode'                  => '123456',
        //         'CountryCode'               => 'LK'
        //     ],                      
        //     'DestinationAddress' => [
        //         'PostCode'              => '123456',
        //         'CountryCode'           => 'LK'
        //     ],
        //     'ShipmentDetails' => [
        //         'PaymentType'            => 'P',
        //         'ProductGroup'           => 'DOM',
        //         'ProductType'            => 'OND',
        //         'ActualWeight'           => array('Value' => 1, 'Unit' => 'KG'),
        //         'ChargeableWeight'       => array('Value' => 1, 'Unit' => 'KG'),
        //         'NumberOfPieces'         => 1,

        //     ]
        // ];
        // $soapClient = new \SoapClient(module_path('aramex').'/Config/aramex-rates-calculator-wsdl.wsdl', array('trace' => 1));
        // $results = $soapClient->CalculateRate($params);
    

        // dd($results);
        // dd($results, $results->Notifications, $results->Notifications->Notification->Message); 
        // $c = session('store')->configurations->pluck('value', 'name')->all();
        // dd($c);
        $shipping_zones = $request->tenant()->shippingZones;
        $shipping_classes = $request->tenant()->shippingClasses;
        $shippers = $this->getStoreShippingPlugins($request->tenant());

        return view('product::shippings.index', compact('shipping_zones', 'shipping_classes', 'shippers'));
    }


    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $countries = Country::where('status', 1)->get();
        $country_states = Country::where('contains_states', 1)->orderBy('name', 'asc')->get();
        $shipping_methods = ShippingMethod::where('status',1)->get();
        return view('product::shippings.create', compact('countries','country_states','shipping_methods'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(ShippingZoneFormRequest $request)
    {
        $shipping_zone = new ShippingZone;
        $shipping_zone->zone_name = $request->zone_name;
        $shipping_zone->alias = str_slug($request->zone_name,'-');
        $shipping_zone->zone_type = $request->zone_type;
        $shipping_zone->status = $request->zone_status ? 1 : 0;
        $shipping_zone->save();

        if($request->zone_type == 'country') { 
            $this->storeCountryZone($request->zone_country, $shipping_zone, $request->tenant());
        } else if ($request->zone_type == 'state') { 
            $this->storeStateZone($request->states, $shipping_zone, $request->tenant());
        } else if ($request->zone_type == 'zip_code') { 
            $this->storeZipCodeZone($request, $shipping_zone, $request->tenant());
        }
        $this->storeShippingOptions($shipping_zone, $request->tenant());

        return redirect()->route('shipping.edit', $shipping_zone)->withSuccess('Shipping zone created successfully!');
    }

    protected function storeShippingOptions (ShippingZone $shipping_zone, Store $store) {
        $shipping_methods = ShippingMethod::where('status',1)->get();
        foreach($shipping_methods as $method) {
            $shipping_zone_method  = new ShippingZoneMethod;
            $shipping_zone_method->shippingZone()->associate($shipping_zone);
            $shipping_zone_method->shippingMethod()->associate($method->id);
            if($method->id == 1) {
                 $shipping_zone_method->status = 1;
            }
            $shipping_zone_method->display_name = $method->name;
            $shipping_zone_method->save();
        }
    }

    protected function storeCountryZone (array $countries, ShippingZone $shipping_zone, Store $store) 
    {
        for ($i=0; $i < count($countries); $i++) { 
            $shipping_zone_location = new ShippingZoneLocation;
            $shipping_zone_location->shippingZone()->associate($shipping_zone);
            $shipping_zone_location->country()->associate($countries[$i]);
            $shipping_zone_location->created_at_tz = $shipping_zone_location->freshTimestamp()->timezone($store->setting->timezone->timezone);
            $shipping_zone_location->updated_at_tz = $shipping_zone_location->freshTimestamp()->timezone($store->setting->timezone->timezone);
            $shipping_zone_location->save();
        }
    }

    protected function storeStateZone (array $states, ShippingZone $shipping_zone, Store $store)
    {
        for ($i=0; $i < count($states); $i++) { 
            $data = explode('-',$states[$i]);
            $shipping_zone_location = new ShippingZoneLocation;
            $shipping_zone_location->shippingZone()->associate($shipping_zone);
            $shipping_zone_location->country()->associate($data[0]);
            $shipping_zone_location->state()->associate($data[1]);
            $shipping_zone_location->created_at_tz = $shipping_zone_location->freshTimestamp()->timezone($store->setting->timezone->timezone);
            $shipping_zone_location->updated_at_tz = $shipping_zone_location->freshTimestamp()->timezone($store->setting->timezone->timezone);
            $shipping_zone_location->save();
        }
    }

    protected function storeZipCodeZone (ShippingZoneFormRequest $request, ShippingZone $shipping_zone, Store $store) 
    {
        $shipping_zone_location = new ShippingZoneLocation;
        $shipping_zone_location->shippingZone()->associate($shipping_zone);
        $shipping_zone_location->country()->associate($request->zip_country);

        if(is_array($request->zip_code)) {
            $shipping_zone_location->zip_codes = implode(',', $request->zip_code);
        } else {
            $shipping_zone_location->zip_codes = $request->zip_code;
        }

        $shipping_zone_location->created_at_tz = $shipping_zone_location->freshTimestamp()->timezone($store->setting->timezone->timezone);
        $shipping_zone_location->updated_at_tz = $shipping_zone_location->freshTimestamp()->timezone($store->setting->timezone->timezone);
        $shipping_zone_location->save();
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function configStorePickup(StorePickupFormRequest $request)
    {

        $request->tenant()->setting->enable_store_pickup = $request->store_pickup;
        $request->tenant()->setting->store_pickup_display_name = $request->display_name;
        $request->tenant()->setting->store_pickup_instructions = htmlentities($request->instructions);
        $request->tenant()->setting->save();

        return redirect()->back()->withSuccess('Store pickup updated successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(Request $request, ShippingZone $shipping_zone)
    {
        $countries = Country::where('status', 1)->get();
        $country_states = Country::where('contains_states', 1)->orderBy('name', 'asc')->get();
        $shipping_methods = ShippingMethod::where('status',1)->get();
        $method = $shipping_zone->shippingMethods->where('shipping_method_id',3)->first();
        $data = '';

        if($method) {
            $data = fractal()->item($method)->transformWith(new ShippingZoneMethodTransformer)->toJson();
        }
         
        return view('product::shippings.edit',compact('shipping_zone', 'countries', 'country_states', 'shipping_methods', 'data'));
    }

    protected function getStoreShippingPlugins(Store $store)
    {
        $store_plugins = $store->plugins;
        $plugins = collect([]);

        foreach($store_plugins as $module) {

            if($module->plugin->category->alias === 'shipping') {

                $plugins->push($module);
            }
        }   
        
        return $plugins;
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(ShippingZoneFormRequest $request, ShippingZone $shipping_zone)
    {
        $shipping_zone->zone_name = $request->zone_name;
        $shipping_zone->alias = str_slug($request->zone_name,'-');
        $shipping_zone->zone_type = $request->zone_type;
        $shipping_zone->status = $request->zone_status ? 1 : 0;
        $shipping_zone->save();
        $this->refreshZones($shipping_zone->locations);

        if($request->zone_type == 'country') { 
            $this->storeCountryZone($request->zone_country, $shipping_zone, $request->tenant());
        } else if ($request->zone_type == 'state') { 
            $this->storeStateZone($request->states, $shipping_zone, $request->tenant());
        } else if ($request->zone_type == 'zip_code') { 
            $this->storeZipCodeZone($request, $shipping_zone, $request->tenant());
        }
       
        return redirect()->route('shipping.index')->withSuccess('Shipping zone updated successfully!');
    }

    protected function refreshZones($locations)
    {
        foreach($locations as $location) {
            $location->delete();
        }
    }

    public function destroy(ShippingZone $shipping_zone)
    {
        $shipping_zone->delete();
    }

}
