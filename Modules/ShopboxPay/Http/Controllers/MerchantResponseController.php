<?php

namespace Modules\ShopboxPay\Http\Controllers;

use Cookie;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Order\Entities\Order;
use Shopbox\Mail\Order\OrderConfirmation;
use Modules\Product\Traits\StockTrait;
use Shopbox\Traits\Stock;

class MerchantResponseController extends Controller
{
    use StockTrait, Stock;

    public function __construct()
    {
        $this->agent = new Agent();
    }

    public function index(Request $request)
    {   
        $response = $request->response;
        $order_id = explode('-', $response['data']['order_id']);
        $order = Order::where('store_id', $order_id[0])->where('order_id', $order_id[1])->first();

        if(!$order) {
            $response['data']['status_desc'] = 'Order id not found.';
            return view('shopboxpay::errors.error', compact('response', 'store'));
        }

        $store = $order->store;
           
        if($response['data']['status'] == 1) {

            $hash = $store->client->id.$store->client->secret.$response['data']['order_id'].$response['data']['transaction_id'];
            $signature = base64_encode(pack('H*', sha1($hash)));

            if($signature === $response['data']['signature']) {
                $this->updateOrder($order, $response);
                Mail::to($store->trans_email)->queue(new OrderConfirmation($order));
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
                    updateOrderStatus($order, 'payment-received');
                    updateOrderStatus($order, 'pending');
                }

                Cookie::queue(Cookie::forget('cart', '/', getStoreDomain($store)));

            }

        } else {
           updateOrderStatus($order, 'payment-error');
        }
        
    }

    protected function updateOrder(Order $order, $response)
    {
        $order->total_real_paid = $order->total_paid;
        $order->status = 1;
        $order->save();

        $order->payment->payment_gateway = $response['data']['gateway'];
        $order->payment->transaction_id = $response['data']['transaction_id'];
        $order->payment->card_number = $response['data']['last4'];
        $order->payment->card_brand = $response['data']['type'];
        $order->payment->created_at_tz = $order->freshTimestamp()->timezone($order->store->setting->timezone->timezone);
        $order->payment->created_at = $order->freshTimestamp();
        $order->payment->save();
    }

}

