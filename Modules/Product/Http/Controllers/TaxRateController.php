<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Product\Http\Requests\Tax\Rate\TaxRateFormRequest;
use Modules\Product\Entities\TaxClass;
use Modules\Product\Entities\TaxZone;
use Modules\Product\Entities\Tax;
use Modules\Product\Entities\TaxRate;

class TaxRateController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('product::taxes.rates.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $tax_classes = TaxClass::all();
        $tax_zones = TaxZone::all();
        return view('product::taxes.rates.create', compact('tax_zones','tax_classes'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(TaxRateFormRequest $request)
    {
        $tax = new Tax;
        $tax->zone()->associate($request->tax_zone);
        $tax->name = $request->tax_name;
        $tax->priority = $request->calculation_order;
        $tax->status = $request->status;
        $tax->created_at_tz = $tax->freshTimestamp()->timezone($request->tenant()->setting->timezone->timezone);
        $tax->updated_at_tz = $tax->freshTimestamp()->timezone($request->tenant()->setting->timezone->timezone);
        $tax->save();

        foreach ($request->rates as $key => $value) { 
            $tax_rate = new TaxRate;
            $tax_rate->tax()->associate($tax);
            $tax_rate->taxClass()->associate($key);
            $tax_rate->rate = $value / 100;
            $tax_rate->created_at_tz = $tax_rate->freshTimestamp()->timezone($request->tenant()->setting->timezone->timezone);
            $tax_rate->updated_at_tz = $tax_rate->freshTimestamp()->timezone($request->tenant()->setting->timezone->timezone);
            $tax_rate->save();
        }

        return redirect()->route('tax.rates.index')->withSuccess('Tax rate created successfully!');
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
    public function edit(Tax $tax)
    {
        $tax_classes = TaxClass::all();
        $tax_classes = $tax_classes->whereNotIn('id', $tax->rates->pluck('tax_class_id'));

        return view('product::taxes.rates.edit', compact('tax','tax_classes'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(TaxRateFormRequest $request, Tax $tax)
    {
        $tax->zone()->associate($request->tax_zone);
        $tax->name = $request->tax_name;
        $tax->priority = $request->calculation_order;
        $tax->status = $request->status;
        $tax->updated_at_tz = $tax->freshTimestamp()->timezone($request->tenant()->setting->timezone->timezone);
        $tax->save();
        foreach ($request->rates as $key => $value) { 
            $ids = explode(',', $key);

            if(count($ids) == 2) {

                $tax_rate = TaxRate::find($ids[0]);

                if(!$tax_rate) {
                    return;
                }

                $tax_rate->taxClass()->associate($ids[1]);

            } else if(count($ids) == 1) {

                $tax_rate = new TaxRate;
                $tax_rate->taxClass()->associate($ids[0]);
            }

            $tax_rate->tax()->associate($tax);
            $tax_rate->rate = $value ? $value / 100 : 0;
            $tax_rate->updated_at_tz = $tax_rate->freshTimestamp()->timezone($request->tenant()->setting->timezone->timezone);
            $tax_rate->save();
           
        }

        return redirect()->route('tax.rates.index')->withSuccess('Tax rate updated successfully!');
    }


}
