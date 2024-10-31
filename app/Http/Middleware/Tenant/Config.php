<?php

namespace Shopbox\Http\Middleware\Tenant;

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
        $tenant = $request->tenant();
        config()->set('app.name', $tenant->store_name);
        config()->set('app.url', getStoreUrl($tenant));
        // config()->set('session.domain', session('store')->domain.'.'.config('domain.app_domain'));
        // config()->set('session.domain', config('domain.app_domain'));

        if(session('store')->setting->enable_password && !$request->hasCookie('storefront_digest')) {
            $storefront_digest = cookie('storefront_digest', session('store')->setting->password_hash, 525600 * 2, '/', getStoreDomain(session('store')), false, false);

            return $next($request)->cookie($storefront_digest);
        }

        return $next($request);
    }
}
