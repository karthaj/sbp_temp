<?php

namespace Modules\HNB\Http\Controllers;

use Illuminate\Http\Request;
use Modules\HNB\Http\Requests\HNBStoreFormRequest;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Shopbox\Models\Zpanel\Plugin;
use Shopbox\Models\Zpanel\Currency;
use Shopbox\Models\Zpanel\Configuration;
use Shopbox\Models\Zpanel\StorePayment;
use Modules\Order\Entities\Order;

class HNBController extends Controller
{

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(HNBStoreFormRequest $request)
    {
      
        $plugin = Plugin::where('alias', 'hnb')->first();

        if($request->tenant()->payments()->where('plugin_id', $plugin->id)->count()) {
            $payment = $request->tenant()->payments()->where('plugin_id', $plugin->id)->first();
        } else {
            $payment = new StorePayment;
        }

        $payment->store()->associate($request->tenant());
        $payment->plugin()->associate($plugin);
        $payment->display_name = $request->display_name;
        $payment->save();


        $credentials = collect([
            ['name' => 'HNB_VERSION', 'value' => '1.0.0'],
            ['name' => 'HNB_MERCHANT_ID', 'value' => $request->merchant_id],
            ['name' => 'HNB_MERCHANT_PASSWORD', 'value' => $request->password],
            ['name' => 'HNB_ACQUIRER_ID', 'value' => $request->acquirer_id],
            ['name' => 'HNB_CURRENCY', 'value' => $request->currency],
            ['name' => 'HNB_TEST_MODE', 'value' => $request->test_mode ? 'True' : 'False'],
            ['name' => 'HNB_SIGNATURE_METHOD', 'value' => 'SHA1'],
            ['name' => 'HNB_CAPTURE_FLAG', 'value' => 'A'],
        ]);

        foreach($credentials as $credential) {

            $configuration = $request->tenant()->configurations()->where('name', $credential['name'])->first();

            if(!$configuration) {
                $configuration = new Configuration;
            } 

            $configuration->store()->associate($request->tenant());
            $configuration->name = $credential['name'];
            $configuration->value = $credential['value'];
            $configuration->save();
        }

        return redirect()->route('payments.index')->withSuccess('Payment settings updated successfully.');

    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(Request $request)
    {
        $plugin = Plugin::where('slug', 'hnb')->first();
        $currencies = Currency::where('status', 1)->get();
        $configs = $request->tenant()->configurations;
        $configs = $configs->pluck('value', 'name');
        $configs = $configs->all();

        if($request->tenant()->payments()->where('plugin_id', $plugin->id)->count()) {
            $display_name = $request->tenant()->payments()->where('plugin_id', $plugin->id)->first()->display_name;
        } else {
            abort(401);
        }

        return view('hnb::edit', compact('plugin', 'currencies', 'configs', 'display_name'));
    }

    public function show(Order $order)
    {
        return view('hnb::ipg', compact('order'));
    }

}
