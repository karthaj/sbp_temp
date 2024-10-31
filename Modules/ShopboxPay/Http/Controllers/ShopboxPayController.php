<?php

namespace Modules\ShopboxPay\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Shopbox\Models\Zpanel\StorePayment;
use Modules\ShopboxPay\Entities\Config;
use Modules\Order\Entities\Order;
use Shopbox\Models\Zpanel\Configuration;

class ShopboxPayController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('shopboxpay::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('shopboxpay::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show(Order $order)
    {
        return view('shopboxpay::ipg', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(Request $request)
    {
        $shopboxpay = $request->tenant()->payments()->where('shopbox_ipg', 1)->first();
        $configs = $request->tenant()->configurations;
        $configs = $configs->pluck('value', 'name');
        $configs = $configs->all();
        $service_blocked = false;

        if($request->redirect_url) {
            $request->tenant()->client->update([
                'redirect' => $request->redirect_url
            ]);
        }

        foreach($shopboxpay->payments as $payment) {
            if(!$payment->live) {
                $service_blocked = true;
                break;
            }
        }

        return view('shopboxpay::edit', compact('shopboxpay', 'configs', 'service_blocked'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
        $data = $request->data;

        foreach ($data as $value) {
            Config::where('id', $value['payment'])->update([
                'active' => $value['status']
            ]);
        }

        $credentials = collect([
            ['name' => 'SHOPBOXPAY_ACCOUNT_NAME', 'value' => $request->account_name],
            ['name' => 'SHOPBOXPAY_ACCOUNT_NUMBER', 'value' => $request->account_number],
            ['name' => 'SHOPBOXPAY_BANK_NAME', 'value' => $request->bank_name],
            ['name' => 'SHOPBOXPAY_BANK_BRANCH', 'value' => $request->bank_branch],
            ['name' => 'SHOPBOXPAY_SWIFT_CODE', 'value' => $request->swift_code]
        ]);

        foreach($credentials as $credential) {

            $configuration = $request->tenant()->configurations()->where('name', $credential['name'])->first();

            if(!$configuration) {
                $configuration = new Configuration;
            } 

            $configuration->store()->associate($request->tenant());
            $configuration->name = $credential['name'];
            $configuration->value = $credential['value'] ? : '';
            $configuration->save();
        }

        return redirect()->route('payments.index')->withSuccess('Payment settings updated successfully.');
    }   

}
