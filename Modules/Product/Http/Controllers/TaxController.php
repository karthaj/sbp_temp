<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Product\Http\Requests\Tax\TaxClassFormRequest;
use Modules\Product\Http\Requests\Tax\TaxConfigFormRequest;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\TaxClass;
use Modules\Product\Entities\TaxOption;

class TaxController extends Controller
{
    
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('product::taxes.classes.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('product::taxes.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(TaxClassFormRequest $request)
    {
        TaxClass::create(['name' => $request->tax_class]);
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
    public function edit()
    {
        $tax_option = TaxOption::first();
        $tax_classes = TaxClass::all();
        return view('product::taxes.edit',compact('tax_option','tax_classes'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(TaxClassFormRequest $request, TaxClass $tax_class)
    {
         $tax_class->name = $request->tax_class;
         $tax_class->save();
    }

     /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function updateTaxOption(TaxConfigFormRequest $request, TaxOption $tax_option)
    {
        $tax_option->update([
            'shipping_tax' => $request->shipping_tax,
            'tax_label' => $request->tax_label,
            'charge_tax' => $request->charge_tax ? $request->charge_tax : 0,
            'price_includes_tax' => $request->price_tax,
            'tax_based_on' => $request->calculate_tax,
            'tax_display_product_listing' => $request->product_listing_tax,
            'tax_display_product_page' => $request->product_page_tax,
            'tax_display_cart' => $request->cart_tax,
            'tax_display_order_invoice' => $request->order_invoice_tax,
            'display_tax_charge_cart' => $request->cart_charge,
            'display_tax_charge_order' => $request->order_tax,
            'created_at_tz' => $tax_option->freshTimestamp()->timezone($request->tenant()->setting->timezone->timezone),
            'updated_at_tz' => $tax_option->freshTimestamp()->timezone($request->tenant()->setting->timezone->timezone),
        ]);

        return redirect()->route('tax.edit')->withSuccess('Tax configured successfully!');
    }

   
}
