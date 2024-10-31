<?php

namespace Modules\BankTransfer\Http\Controllers;

use Illuminate\Http\Request;
use Modules\BankTransfer\Http\Requests\BankTransferFormRequest;
use Illuminate\Http\Response;
use Shopbox\Models\Zpanel\Plugin;
use Shopbox\Models\Zpanel\Configuration;
use Shopbox\Models\Zpanel\StorePayment;
use Illuminate\Routing\Controller;

class BankTransferController extends Controller
{

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(BankTransferFormRequest $request)
    {
        $plugin = Plugin::where('alias', 'banktransfer')->first();

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
            ['name' => 'BANK_TRANSFER_ACCOUNT_NAME', 'value' => $request->account_name],
            ['name' => 'BANK_TRANSFER_ACCOUNT_NUMBER', 'value' => $request->account_number],
            ['name' => 'BANK_TRANSFER_BANK_NAME', 'value' => $request->bank_name],
            ['name' => 'BANK_TRANSFER_BANK_BRANCH', 'value' => $request->bank_branch],
            ['name' => 'BANK_TRANSFER_SWIFT_CODE', 'value' => $request->swift_code]
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
        $plugin = Plugin::where('alias', 'banktransfer')->first();
        $configs = $request->tenant()->configurations;
        $configs = $configs->pluck('value', 'name');
        $configs = $configs->all();
        $display_name = '';

        if($request->tenant()->payments()->where('plugin_id', $plugin->id)->count()) {
            $display_name = $request->tenant()->payments()->where('plugin_id', $plugin->id)->first()->display_name;
        }

        return view('banktransfer::index', compact('plugin', 'configs', 'display_name'));
    }
}
