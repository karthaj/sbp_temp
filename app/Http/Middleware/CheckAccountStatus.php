<?php

namespace Shopbox\Http\Middleware;

use Closure;
use Shopbox\Models\Zpanel\Store;

class CheckAccountStatus
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
        $store = Store::find(session('tenant'));
        $redirect = checkAccountStatus($store);

        if(!$redirect) {
            return redirect()->route('dashboard');
        }

        return $next($request);
    }
}
