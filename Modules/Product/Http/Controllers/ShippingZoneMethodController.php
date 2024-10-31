<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Product\Http\Requests\Shipping\FreeShippingFormRequest;
use Modules\Product\Http\Requests\Shipping\FlatRateFormRequest;
use Modules\Product\Http\Requests\Shipping\StorePickupFormRequest;
use Modules\Product\Http\Requests\Shipping\ShippingMethodRequest;
use Modules\Product\Http\Requests\Shipping\DeliveryRateFormRequest;
use Modules\Product\Http\Requests\Shipping\StorePickupStatusRequest;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Product\Entities\ShippingMethod;
use Modules\Product\Entities\ShippingZone;
use Modules\Product\Entities\ShippingZoneMethod;

class ShippingZoneMethodController extends Controller
{

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('product::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        $shipping_methods = ShippingMethod::where('status',1)->get();
        return view('product::shippings.create_shipping_option', compact('shipping_methods'));
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
    public function edit(ShippingZone $shipping_zone)
    {   
        $shipping_methods = ShippingMethod::where('status',1)->get();
        return view('product::shippings.edit_shipping_option',compact('shipping_zone','shipping_methods'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function updateFreeShipping(FreeShippingFormRequest $request, ShippingZoneMethod $shipping_zone_method)
    {
        $shipping_zone_method->email = $request->email;
        $shipping_zone_method->min_order = $request->limit_order ? $request->amount : 0;
        $shipping_zone_method->is_free = 1;
        $shipping_zone_method->updated_at_tz = $shipping_zone_method->freshTimestamp()->timezone($request->tenant()->setting->timezone->timezone);
        $shipping_zone_method->save();

        return response()->json([
            'data' => [
                'rate' => $shipping_zone_method->min_order,
            ]
        ]);
    }

     /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function updateFlatRate(FlatRateFormRequest $request, ShippingZoneMethod $shipping_zone_method)
    {
        $shipping_zone_method->email = $request->flat_rate_email;
        $shipping_zone_method->display_name = $request->display_name;
        $shipping_zone_method->rate = $request->shipping_rate;
        $shipping_zone_method->eligible_type = $request->charge_type;
        $shipping_zone_method->updated_at_tz = $shipping_zone_method->freshTimestamp()->timezone($request->tenant()->setting->timezone->timezone);
        $shipping_zone_method->save();

        return response()->json([
            'data' => [
                'rate' => $shipping_zone_method->rate,
            ]
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function updateStorePickup(StorePickupFormRequest $request, ShippingZoneMethod $shipping_zone_method)
    {
        $shipping_zone_method->email = $request->store_pickup_email;
        $shipping_zone_method->display_name = $request->display_name;
        $shipping_zone_method->save();
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, ShippingZoneMethod $shipping_zone_method)
    {
        $shipping_zone_method->shippingZone->shippingMethods()->where('shipping_method_id', '<>', 4)->update([
            'status' => 0
        ]);

        $shipping_zone_method->status = $request->status;
        $shipping_zone_method->save();

        if($shipping_zone_method->status) 
            $message = $shipping_zone_method->display_name.' activated successfully.';
        else
            $message = $shipping_zone_method->display_name.' deactivated successfully.';

        return response()->json(compact('message'));
    }

     /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function updateStorePickupStatus(Request $request, ShippingZoneMethod $shipping_zone_method)
    {
        $shipping_zone_method->status = $request->status;
        $shipping_zone_method->save();

        if($shipping_zone_method->status) 
            $message = $shipping_zone_method->display_name.' activated successfully.';
        else
            $message = $shipping_zone_method->display_name.' deactivated successfully.';

        return response()->json(compact('message'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function updateDeliveryRate(DeliveryRateFormRequest $request, ShippingZoneMethod $shipping_zone_method)
    {
        $shipping_zone_method->email = $request->email;
        $shipping_zone_method->display_name = $request->display_name;
        $shipping_zone_method->need_range = 1;
        $shipping_zone_method->restriction_type = $request->charge_shipping;
        $shipping_zone_method->updated_at_tz = $shipping_zone_method->freshTimestamp()->timezone($request->tenant()->setting->timezone->timezone);
        $shipping_zone_method->save();
        if($shipping_zone_method->deliveryRates->count()) {
             $shipping_zone_method->deliveryRates()->delete();
        }
        $ranges = $request->ranges;
        foreach ($ranges as $range) {
            $shipping_zone_method->deliveryRates()->create([
                'delimiter1' => $range['from'],
                'delimiter2' => $range['to'],
                'price' => $range['cost'],
                'created_at_tz' => $shipping_zone_method->updated_at_tz = $shipping_zone_method->freshTimestamp()->timezone($request->tenant()->setting->timezone->timezone),
                'updated_at_tz' => $shipping_zone_method->updated_at_tz = $shipping_zone_method->freshTimestamp()->timezone($request->tenant()->setting->timezone->timezone),
            ]);
        }
    }

}
