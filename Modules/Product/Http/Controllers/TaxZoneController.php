<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Shopbox\Models\Zpanel\Country;
use Shopbox\Models\Zpanel\Store;
use Modules\Product\Http\Requests\Tax\Zone\ZoneFormRequest;
use Modules\Product\Entities\TaxZone;
use Modules\Product\Entities\TaxRule;

class TaxZoneController extends Controller
{
    

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('product::taxes.zones.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $countries = Country::where('status', 1)->get();
        $country_states = Country::where('contains_states', 1)->orderBy('name', 'asc')->get();
        return view('product::taxes.zones.create', compact('countries','country_states'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(ZoneFormRequest $request)
    { 
        $tax_zone = new TaxZone;
        $tax_zone->type = $request->zone_type;
        $tax_zone->name = strtolower($request->zone_name);
        $tax_zone->status = $request->zone_status;
        $tax_zone->save();
        // check zone type
        if($request->zone_type == 'country') { 
            $this->storeCountryZone($request->zone_country, $tax_zone, $request->tenant());
        } else if ($request->zone_type == 'state') { 
            $this->storeStateZone($request->states, $tax_zone, $request->tenant());
        } else if ($request->zone_type == 'zip') { 
            $this->storeZipCodeZone($request, $tax_zone, $request->tenant());
        }
        return redirect()->route('tax.zones.index')->withSuccess('Zone created successfully!');
    } 

    protected function storeCountryZone (array $countries, TaxZone $tax_zone, Store $store) 
    {
        for ($i=0; $i < count($countries); $i++) { 
            $tax_rule = new TaxRule;
            $tax_rule->taxZone()->associate($tax_zone->id);
            $tax_rule->country()->associate($countries[$i]);
            $tax_rule->save();
        }
    }

    protected function storeStateZone (array $states, TaxZone $tax_zone, Store $store)
    {
        for ($i=0; $i < count($states); $i++) { 
            $data = explode('-',$states[$i]);
            $tax_rule = new TaxRule;
            $tax_rule->taxZone()->associate($tax_zone->id);
            $tax_rule->country()->associate($data[0]);
            $tax_rule->state()->associate($data[1]);
            $tax_rule->save();
        }
    }

    protected function storeZipCodeZone (ZoneFormRequest $request, TaxZone $tax_zone, Store $store) 
    {
        $tax_rule = new TaxRule;
        $tax_rule->taxZone()->associate($tax_zone);
        $tax_rule->country()->associate($request->zip_country);
        $tax_rule->zip_codes = $request->zip_code;
        $tax_rule->save();
    }

    protected function refreshZones(TaxZone $tax_zone)
    {
        foreach($tax_zone->taxRules as $rule) {
            $rule->delete();
        }
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('product::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(TaxZone $tax_zone)
    {
        if($tax_zone->store->id != session('tenant')) {
            return abort('404');
        }
        $countries = Country::where('status', 1)->get();
        $country_states = Country::where('contains_states', 1)->orderBy('name', 'asc')->get();
        return view('product::taxes.zones.edit',compact('tax_zone', 'countries', 'country_states'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(ZoneFormRequest $request, TaxZone $tax_zone)
    {
        $tax_zone->type = $request->zone_type;
        $tax_zone->name = $request->zone_name;
        $tax_zone->status = $request->zone_status;
        $tax_zone->save();
        $this->refreshZones($tax_zone);
        // check zone type
        if($request->zone_type == 'country') { 
            $this->storeCountryZone($request->zone_country, $tax_zone, $request->tenant());
        } else if ($request->zone_type == 'state') { 
            $this->storeStateZone($request->states, $tax_zone, $request->tenant());
        } else if ($request->zone_type == 'zip') { 
            $this->storeZipCodeZone($request, $tax_zone, $request->tenant());
        }
        return redirect()->route('tax.zones.index')->withSuccess('Zone updated successfully!');
    }

    
}
