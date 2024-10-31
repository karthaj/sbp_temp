<?php

namespace Modules\HNB\Http\Controllers;

use Cookie;
use Config;
use Shopbox\Traits\Stock;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\HNB\Entities\HNB;
use Modules\Order\Entities\Order;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Modules\Product\Traits\StockTrait;
use Modules\Order\Entities\OrderPayment;
use Shopbox\Mail\Order\OrderConfirmation;

class ResponseController extends Controller
{   
    use StockTrait, Stock;

    public function __construct()
    {
        $this->agent = new Agent();
    }

    public function index(Request $request)
    {
        
        $data = $request->all();
        $order = session('store')->orders()->where('order_id', $data['OrderID'])->first();

        $config = $order->store->configurations;
        $config = $config->pluck('value', 'name');
        $config = $config->all();

        $pass = $config['HNB_MERCHANT_PASSWORD'].$config['HNB_MERCHANT_ID'].$config['HNB_ACQUIRER_ID'].$data['OrderID'].$data['ResponseCode'];
        $signature = base64_encode(pack('H*', sha1($pass)));

        if($data['ResponseCode'] == 1 && $data['Signature'] === $signature) {

            $this->saveTransaction($order, $data);
            $this->saveOrderPayment($order, $data);
            Mail::to(session('store')->trans_email)->queue(new OrderConfirmation($order));
            Mail::to($order->customer->customerEmail)->queue(new OrderConfirmation($order));

            $checkout_session = json_decode($order->cart->checkout_session_data, true);
            $checkout_session['checkout-payment-step']['step_is_complete'] = true;
            $checkout_session['checkout-payment-step']['step_is_reachable'] = true;
            $order->cart->checkout_session_data = json_encode($checkout_session);
            $order->cart->stock_reserved = 0;
            $order->cart->save();

            $this->restock($order->cart);
            $this->adjustStocks($order);

            if(!$order->cart->requiredShipping()) {
                updateOrderStatus($order, 'completed');
            } else {
                updateOrderStatus($order, 'pending');
            }

            $cookie = Cookie::forget('cart', '/', config('session.domain'));

            return redirect()->route('checkout.order.confirmation', $order)->cookie($cookie);
        } 

        
        if($order) {
            if(!$order->cart->requiredShipping()) {
                updateOrderStatus($order, 'payment-error');
            }
        }

        return view('hnb::error', compact('data', 'store'));
    }

    protected function saveOrderPayment(Order $order, array $response)
    {
        $order->total_real_paid = $order->total_paid;
        $order->status = 1;
        $order->save();

        if(!empty($response['ReferenceNo'])) { 
            $order->payment->transaction_id = $response['ReferenceNo'];
        }

        if(!empty($response['PaddedCardNo'])) { 
            $order->payment->card_number = $response['PaddedCardNo'];
        }

        if(!empty($response['ECIIndicator'])) {
            if($response['ECIIndicator'] == 01 || $response['ECIIndicator'] == 02) {
                $order->payment->card_brand = 'mastercard';
            } else if($response['ECIIndicator'] == 05 || $response['ECIIndicator'] == 06) {
                $order->payment->card_brand = 'visa';
            }
        }
        
        $order->payment->created_at_tz = $order->payment->freshTimestamp()->timezone($order->store->setting->timezone->timezone);
        $order->payment->created_at = $order->payment->freshTimestamp();
        $order->payment->save();
    }

    protected function saveTransaction(Order $order, array $response)
    {

        $transaction = new HNB;
        $transaction->store()->associate($order->store);
        $transaction->order()->associate($order);
        $transaction->merchant_id = $response['MerID'];
        $transaction->auth_code = $response['AuthCode'];
        $transaction->reference_no = $response['ReferenceNo'];
        $transaction->last4 = $response['PaddedCardNo'];

        if(!empty($response['TransactionStain'])) {
            $transaction->transaction_stain = $response['TransactionStain'];
        }

        $transaction->amount = $order->total_paid;

        if(!empty($response['ECIIndicator'])) {
           $transaction->eci_indicator = $response['ECIIndicator'];
        }

        $transaction->state = $response['ReasonCode'];
        $transaction->result = $response['ReasonCodeDesc'];
        $transaction->save();

    }

}
