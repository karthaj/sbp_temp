<?php

namespace Shopbox\Http\Middleware\Store;

use Cookie;
use Closure;

class Config
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
        config()->set('app.url', getStoreUrl(session('store')));
        if(session('store')->main && !session('store')->block_domain) {
            config()->set('session.domain', getStoreDomain(session('store')));
        }
        // config()->set('session.domain', getStoreDomain(session('store')));
        
        return $next($request);
    }
}
