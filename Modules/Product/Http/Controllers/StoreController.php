<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Shopbox\Models\Zpanel\User;
use Shopbox\Models\Zpanel\Country;
use Shopbox\Models\Zpanel\Store;
use Modules\Product\Traits\StockTrait;
use Modules\Product\Entities\StoreLocation;
use Modules\Product\Http\Requests\Store\StoreFormRequest;

class StoreController extends Controller
{
     use StockTrait;

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('product::stores.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $countries = Country::where('status', 1)->get();
        return view('product::stores.create',compact('countries'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(StoreFormRequest $request)
    {
        $store_location = new StoreLocation;
        $store_location->country()->associate($request->country);
        $store_location->state()->associate($request->state);
        $store_location->name = $request->name;
        $store_location->address = $request->address;
        $store_location->city = $request->city;
        $store_location->phone = $request->phone;
        $store_location->postcode = $request->postal_code;
        $store_location->active = $request->status;
        $store_location->online_sales = (int) $request->online_store;
        $store_location->save();

        if((int)($request->online_store)) {
            $this->removeExistingOnlineStore($request->tenant(), $store_location->id);
        }

        if($request->tenant()->users()->where('master', 1)->count()) {

            $user = $request->tenant()->users()->where('master', 1)->first();
            $user->storeLocations()->attach($store_location);
        }

        $store = $store_location->store;

        if($store->stocks->count()) {
            foreach($store->stocks as $stock) {
                $this->incrementStock($stock, 0, $store_location);
            }
        }

        return redirect()->route('stores.index')->withSuccess('Store location created successfully.');
    }

    protected function removeExistingOnlineStore(Store $store, $location_id)
    {
        if($store->storeLocations()->where('id', '<>', $location_id)->where('online_sales', 1)->count()) {
            return (bool)$store->storeLocations()->where('id', '<>', $location_id)->where('online_sales', 1)->first()->update(['online_sales' => 0]);
        }

        return false;
        
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function updateStatus(StoreLocation $store_location, Request $request)
    {

        if($request->store_location) {
            $store = StoreLocation::find($request->store_location);

            if($this->removeExistingOnlineStore($request->tenant())) {
               $store->online_sales = 1;
               $store->save();
            }
           
        }

        $store_location->active = $request->status;
        $store_location->save();
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(StoreLocation $store_location)
    {
        if($store_location->store->id != session('store')->id) {
            return abort('404');
        }

        $countries = Country::where('status', 1)->get();
        return view('product::stores.edit',compact('store_location','countries'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(StoreFormRequest $request, StoreLocation $store_location)
    {
        $store_location->country()->associate($request->country);
        $store_location->state()->associate($request->state);
        $store_location->name = $request->name;
        $store_location->address = $request->address;
        $store_location->city = $request->city;
        $store_location->phone = $request->phone;
        $store_location->postcode = $request->postal_code;
        $store_location->active = $request->status;
        $store_location->online_sales = (int) $request->online_store;
        $store_location->save();
        
        if((int)($request->online_store)) {
            $this->removeExistingOnlineStore($request->tenant(), $store_location->id);
        }

        if($request->tenant()->users()->where('master', 1)->count()) {
            $user = $request->tenant()->users()->where('master', 1)->first();
            if(!$user->storeLocations->contains('id', $store_location->id)) {
                $user->storeLocations()->attach($store_location);
            }
        }
           
        return redirect()->route('stores.index')->withSuccess('Store location updated successfully.');
        
    }


}
