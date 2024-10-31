<?php

namespace Modules\ShopboxPay\Http\Controllers;

use Cookie;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Order\Entities\Order;

class ResponseController extends Controller
{

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

            if($signature !== $response['data']['signature']) {
                $response['data']['status_desc'] = 'Invalid signature.';
                return view('shopboxpay::errors.error', compact('response', 'store'));
            }

            $cookie = Cookie::forget('cart', '/', getStoreDomain($store));

            return redirect()->route('checkout.order.confirmation', $order)->cookie($cookie);

        } else {
            return view('shopboxpay::errors.error', compact('response', 'store'));
        }
        
    }

}
