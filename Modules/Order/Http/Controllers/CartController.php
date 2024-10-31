<?php

namespace Modules\Order\Http\Controllers;

use Shopbox\Traits\Stock;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Product\Entities\Cart;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Mail;
use Modules\Order\Emails\CartRecoveryEmail;
use Shopbox\Transformers\CheckoutTransformer;
use Shopbox\Transformers\Cart\CartTransformer;

class CartController extends Controller
{

    use Stock;
    
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('order::carts.index');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(Request $request, Cart $cart)
    {
        
        $checkout = fractal()->item($cart)->transformWith(new CheckoutTransformer)->toArray()['data'];
        $store = $request->tenant();

        return view('order::carts.edit',compact('checkout', 'store'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function recover(Cart $cart)
    {
        $cart_data = fractal()->item($cart)->transformWith(new CartTransformer)->toArray()['data'];

        if($cart_data['need_shipping'] && $cart->carrier) {

            $cart->carrier()->delete();

            $checkout_session = json_decode($cart->checkout_session_data, true);
            $checkout_session['checkout-shipping-step']['step_is_complete'] = false;
            $checkout_session['checkout-shipping-step']['step_is_reachable'] = true;
            $checkout_session['checkout-payment-step']['step_is_complete'] = false;
            $checkout_session['checkout-payment-step']['step_is_reachable'] = false;
            $cart->checkout_session_data = json_encode($checkout_session);

        } else {

            $checkout_session = json_decode($cart->checkout_session_data, true);
            $checkout_session['checkout-payment-step']['step_is_complete'] = false;
            $checkout_session['checkout-payment-step']['step_is_reachable'] = true;
            $cart->checkout_session_data = json_encode($checkout_session);

        }

        if($cart->discounts->count()) {
            $cart->discounts->detach();
        }

        if($cart->customer) {
          Mail::to($cart->customer->customerEmail)->queue(new CartRecoveryEmail($cart));
        }

        return response()->json([
            'data' => 'Mail sent successfully.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy(Cart $cart)
    {
      if(!$cart) {
        return redirect()->route('orders.abandoned.carts.index')->withError('Something went wrong!');
      }

      if($cart->stock_reserved) {
        $this->restock($cart);
      }
      
      $cart->delete();

      return redirect()->route('orders.abandoned.carts.index')->withSuccess('Cart deleted successfully!');

    }

    public function restockProducts(Cart $cart)
    {
     
        if($cart->stock_reserved) {
            $this->restock($cart);
        }

        $cart_data = fractal()->item($cart)->transformWith(new CartTransformer)->toArray()['data'];

        if($cart_data['need_shipping'] && $cart->carrier) {

            $cart->carrier()->delete();

            $checkout_session = json_decode($cart->checkout_session_data, true);
            $checkout_session['checkout-shipping-step']['step_is_complete'] = false;
            $checkout_session['checkout-shipping-step']['step_is_reachable'] = true;
            $checkout_session['checkout-payment-step']['step_is_complete'] = false;
            $checkout_session['checkout-payment-step']['step_is_reachable'] = false;
            $cart->checkout_session_data = json_encode($checkout_session);

        } else {

            $checkout_session = json_decode($cart->checkout_session_data, true);
            $checkout_session['checkout-payment-step']['step_is_complete'] = false;
            $checkout_session['checkout-payment-step']['step_is_reachable'] = true;
            $cart->checkout_session_data = json_encode($checkout_session);

        }
        
        $cart->stock_reserved = 0;
        $cart->save();

        if($cart->discounts->count()) {
            $cart->discounts->detach();
        }

        return redirect()->back();

    }

}
