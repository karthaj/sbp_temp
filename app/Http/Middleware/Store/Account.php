<?php

namespace Shopbox\Http\Middleware\Store;

use Closure;

class Account
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $tabs = ['profile', 'address_list', 'add_address', 'order_list', 'view_order', 'store_list', 'return_list', 'return', 'wishlist'];

        if(!$request->has('tab')) {
            return redirect($request->url().'?tab=profile');
        }

        if(!in_array($request->tab, $tabs)) {
            return redirect($request->url().'?tab=profile');
        }

        if($request->tab === 'view_order' || $request->tab === 'return') {

            if($request->tab === 'return') {

                if(!session('store')->setting->enable_returns) {
                    return redirect($request->url().'?tab=order_list');
                } 

            }

            if(!$request->has('order_id')) {
                return redirect($request->url().'?tab=order_list');
            }

            if(!$request->user()->orders()->where('id', $request->order_id)->count()) {
                return redirect($request->url().'?tab=order_list');
            }

        }

        if($request->tab === 'return_list') {

            if(!session('store')->setting->enable_returns) {
                return redirect($request->url().'?tab=order_list');
            } 

        }

        return $next($request);
    }
}
