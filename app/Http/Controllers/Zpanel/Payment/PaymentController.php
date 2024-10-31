<?php

namespace Shopbox\Http\Controllers\Zpanel\Payment;

use Illuminate\Http\Request;
use Shopbox\Models\Zpanel\Store;
use Shopbox\Models\Zpanel\Plugin;
use Shopbox\Http\Controllers\Controller;

class PaymentController extends Controller
{

    public function index(Request $request)
    {
    	$offline_payments = $this->getStoreOfflinePayments($request->tenant());
    	$online_payments = $this->getStoreOnlinePayments($request->tenant());
       
    	return view('zpanel.payments.index', compact('offline_payments', 'online_payments'));
    }

    public function update(Request $request, $plugin)
    {
       $payment = Plugin::where('alias', $plugin)->first();
       $message = 'Something went wrong.';

       if($payment) {
            $payment = $request->tenant()->payments()->where('plugin_id', $payment->id)->first();
            $payment->active = (int) $request->status;
            $payment->save();

            if($payment->active) {
                $message = $payment->display_name.' activated successfully.';
            } else {
                $message = $payment->display_name.' deactivated successfully.';
            }

            return response()->json(compact('message'));
       }

       return response()->json(compact('message'), 404);
    }

    protected function getStoreOnlinePayments(Store $store)
    {
    	$payments = collect();

        foreach($store->payments as $payment) {
            if($payment->plugin->category) {
                if($payment->plugin->category->alias === 'payment' && $payment->plugin->manual_payment == 0) {
                    $payments->push($payment->plugin);
                }
            }
        }
    	
    	return $payments;
    }

    protected function getStoreOfflinePayments(Store $store)
    {
    	$payments = collect();

        foreach($store->payments as $payment) {
            if($payment->plugin->category) {
                if($payment->plugin->category->alias === 'payment' && $payment->plugin->manual_payment == 1) {
                    $payments->push($payment->plugin);
                }
            }
        }

    	return $payments;
    }
}
