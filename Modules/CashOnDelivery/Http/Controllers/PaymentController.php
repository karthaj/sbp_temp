<?php

namespace Modules\CashOnDelivery\Http\Controllers;

use Illuminate\Http\Request;
use Modules\CashOnDelivery\Http\Requests\COD\CODStoreFormRequest;
use Modules\CashOnDelivery\Http\Requests\COD\CODUpdateFormRequest;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Shopbox\Models\Zpanel\Plugin;
use Shopbox\Models\Zpanel\Configuration;
use Shopbox\Models\Zpanel\Country;
use Shopbox\Models\Zpanel\State;
use Modules\CashOnDelivery\Entities\COD;



class PaymentController extends Controller
{

    public function index(Request $request)
    {
        return view('cashondelivery::cod.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request)
    {
        $zones = $request->tenant()->shippingZones()->doesntHave('cod')->get();
        return view('cashondelivery::cod.create', compact('zones'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(CODStoreFormRequest $request)
    {
        $cod = new COD;
        $cod->store()->associate($request->tenant());
        $cod->zone()->associate($request->shipping_zone);

        if($request->surcharge) {
            $cod->surcharge = $request->surcharge;
        }

        $cod->remark = $request->payment_info;
        $cod->status = $request->status ? 1 : 0;
        $cod->save();

        return redirect()->route('cod.index')->withSuccess('Cash On Delivery (COD) activated successfully!');
    }

    public function edit(COD $cod)
    {
        return view('cashondelivery::cod.edit', compact('cod'));
    }

    public function update(COD $cod, CODUpdateFormRequest $request)
    {
        if($request->surcharge) {
            $cod->surcharge = $request->surcharge;
        }

        $cod->remark = $request->payment_info;
        $cod->status = $request->status ? 1 : 0;
        $cod->save();

        return redirect()->route('cod.index')->withSuccess('Record updated successfully!');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('cashondelivery::show');
    }

 
    public function getStatesByCountry(Country $country)
    {
        $states = getStates($country);
        $result = 0;        
        $elem = '';

        if($states->count()) {
            foreach($states as $state) {
                $elem .= '<option value="'.$state->id.'">'.$state->name.'</option>';
            }  
            $result = 1;
        } 

        return response()->json([
            'states' => $elem,
            'result' => $result
        ]);
    }

    public function getCitiesByState(State $state)
    {
        $cities = getCities($state);
        $result = 0;        
        $elem = '';

        if($cities->count()) {
            foreach($cities as $city) {
                $elem .= '<option value="'.$city->id.'">'.$city->city_name.'</option>';
            }  
            $result = 1;
        } 

        return response()->json([
            'states' => $elem,
            'result' => $result
        ]);
    }
}
