<?php

namespace Shopbox\Http\Middleware\Store;

use Closure;

class Agreement
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
        if(auth()->check()) {
            if(!auth()->user()->stores->contains('id', session('store')->id)) {
                return redirect()->route('customer.agreement');
            }
        }
        return $next($request);
    }
}
