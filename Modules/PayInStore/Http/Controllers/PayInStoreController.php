<?php

namespace Modules\PayInStore\Http\Controllers;

use Illuminate\Http\Request;
use Modules\PayInStore\Http\Requests\PayInStoreFormRequest;
use Illuminate\Http\Response;
use Shopbox\Models\Zpanel\Plugin;
use Shopbox\Models\Zpanel\Configuration;
use Shopbox\Models\Zpanel\StorePayment;
use Illuminate\Routing\Controller;

class PayInStoreController extends Controller
{
    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(PayInStoreFormRequest $request)
    {
        $plugin = Plugin::where('alias', 'payinstore')->first();

        if($request->tenant()->payments()->where('plugin_id', $plugin->id)->count()) {
            $payment = $request->tenant()->payments()->where('plugin_id', $plugin->id)->first();
        } else {
            $payment = new StorePayment;
        }

        $payment->store()->associate($request->tenant());
        $payment->plugin()->associate($plugin);
        $payment->display_name = $request->display_name;
        $payment->save();

        $configuration = $request->tenant()->configurations()->where('name', 'PAYINSTORE_PAYMENT_INSTRUCTION')->first();

        if(!$configuration) {
            $configuration = new Configuration;
        } 

        $configuration->store()->associate($request->tenant());
        $configuration->name = 'PAYINSTORE_PAYMENT_INSTRUCTION';
        $configuration->value = $request->payment_instruction;
        $configuration->save();

        return redirect()->route('payments.index')->withSuccess('Payment settings updated successfully.');

    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(Request $request)
    {
        $plugin = Plugin::where('alias', 'payinstore')->first();
        $configs = $request->tenant()->configurations;
        $configs = $configs->pluck('value', 'name');
        $configs = $configs->all();
        $display_name = '';

        if($request->tenant()->payments()->where('plugin_id', $plugin->id)->count()) {
            $display_name = $request->tenant()->payments()->where('plugin_id', $plugin->id)->first()->display_name;
        }

        return view('payinstore::index', compact('plugin', 'configs', 'display_name'));
    }
}
