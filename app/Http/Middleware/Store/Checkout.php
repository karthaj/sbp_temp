<?php

namespace Shopbox\Http\Middleware\Store;

use Closure;
use Cookie;
use Carbon\Carbon;
use Shopbox\Traits\Consignment;
use Shopbox\Transformers\CheckoutTransformer;

class Checkout
{
    use Consignment;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $cart = session('store')->carts()->where('reference', $request->cookie('cart'))->first();
   
        if(!$cart || !$cart->items->count() || !$cart->stock_reserved) {
            if($request->expectsJson()) {
                return response()->json([
                    'status' => 'expired',
                    'return_url' => route('cart.index')
                ], 422);
            }
            return redirect()->route('cart.index');
        } 

        $checkout = fractal()->item($cart)
                            ->transformWith(new CheckoutTransformer)
                            ->toArray()['data'];

        if(!$request->is('checkout') && $checkout['cart']['requires_splitting']) {
            if($request->expectsJson()) {
                return response()->json([
                    'status' => 'expired',
                    'return_url' => route('checkout.index')
                ], 422);
            }

            return redirect()->route('checkout.index');
        }

        $response = $next($request);

        $cart->refresh();
  
        if($cart->carrier) {
            
            $checkout_session = json_decode($cart->checkout_session_data, true);
            $checkout_session['checkout-shipping-step']['step_is_complete'] = false;
            $checkout_session['checkout-shipping-step']['step_is_reachable'] = true;
            $checkout_session['checkout-payment-step']['step_is_complete'] = false;
            $checkout_session['checkout-payment-step']['step_is_reachable'] = false;
            
            $shippings = $this->getShippingQuotes($cart, $this->getShippingZones($cart));
            
            if(count($shippings) && $checkout_session['checkout-shipping-step']['step_is_complete']) {  
            
                $selected_shipping = array_values(array_where($shippings, function ($value, $key) use ($checkout) {
                    return $value['id'] === $checkout['consignment']['id'];
                }));
                
                if(!count($selected_shipping) || $selected_shipping[0]['rate'] !== $checkout['consignment']['rate']) {

                    $cart->carrier()->delete();
                    $cart->checkout_session_data = json_encode($checkout_session);
                    $cart->save();

                    if($request->expectsJson()) {
                        return response()->json([
                            'status' => 'expired',
                            'return_url' => route('checkout.index')
                        ], 422);
                    }

                    return redirect()->route('checkout.index');

                }
            } else if(!count($shippings)) {
                $cart->carrier()->delete();
                $cart->checkout_session_data = json_encode($checkout_session);
                $cart->save();
                
                if($request->expectsJson()) {
                    return response()->json([
                        'status' => 'expired',
                        'return_url' => route('checkout.index')
                    ], 422);
                }

                return redirect()->route('checkout.index');
            }
        }
        
        return $response;
    }
}
