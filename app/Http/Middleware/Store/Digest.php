<?php

namespace Shopbox\Http\Middleware\Store;

use Closure;

class Digest
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
        if(!session('store')->setting->enable_password) {
            return $next($request);
        }

        if($request->is('store/password') && $request->has('_sfd')) {
            return redirect('store/password');
        }

        if($request->path() == '/' && $request->has('_sfd') && $request->input('_sfd') === session('store')->setting->password_hash) {

            $storefront_digest = cookie('storefront_digest', session('store')->setting->password_hash, 525600 * 2, '/', getStoreDomain(session('store')), false, false);

            return redirect()->route('stores.home')->cookie($storefront_digest);

        } 
        
        if(!request()->is('store/password') && !request()->hasCookie('storefront_digest')  && !request()->preview) {
            return redirect('store/password');
        } else if(request()->is('store/password') && request()->hasCookie('storefront_digest') || !session('store')->setting->enable_password) {
            return redirect()->route('stores.home');
        }

        return $next($request);
    }
}
