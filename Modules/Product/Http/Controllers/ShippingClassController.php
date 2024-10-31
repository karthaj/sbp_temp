<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Product\Http\Requests\Shipping\ShippingClassFormRequest;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\ShippingZone;
use Modules\Product\Entities\ShippingClass;

class ShippingClassController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('product::shippings.class.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request)
    {
        $shipping_zones = $request->tenant()->shippingZones;
        return view('product::shippings.class.create', compact('shipping_zones'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(ShippingClassFormRequest $request)
    {
        $shipping_class = new ShippingClass;
        $shipping_class->name = $request->name;
        $shipping_class->slug = str_slug($request->name, '-');
        $shipping_class->status = $request->status;
        $shipping_class->save();
        $shipping_class->shippingZones()->attach($request->shipping_zones);

        return redirect()->route('shipping.index')->withSuccess('Shipping class created successfully!');

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
    public function edit(ShippingClass $shipping_class)
    {
        $shipping_zones = ShippingZone::all();
        return view('product::shippings.class.edit', compact('shipping_class','shipping_zones'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(ShippingClassFormRequest $request, ShippingClass $shipping_class)
    {
        $shipping_class->name = $request->name;
        $shipping_class->slug = str_slug($request->name, '-');
        $shipping_class->status = $request->status;
        $shipping_class->save();
        $this->detachShippingZones($shipping_class);
        $shipping_class->shippingZones()->attach($request->shipping_zones);

        return redirect()->route('shipping.index')->withSuccess('Shipping class updated successfully!');
    }

    protected function detachShippingZones(ShippingClass $shipping_class)
    {
        foreach($shipping_class->shippingZones as $zone) {
            $shipping_class->shippingZones()->detach($zone->id);
        }
    }

    public function destroy(ShippingClass $shipping_class)
    {
        $shipping_class->delete();
    }


}
