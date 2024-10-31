<?php

namespace Shopbox\Http\Middleware\Tenant;

use Closure;

class CheckBillingAddressFilled
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
        if(!$request->tenant()->hasBillingAddress()) {
            return redirect()->route('settings.edit', 'redirect='.urlencode(url()->previous()))->with('info', 'Please complete your store billing address.');
        }
        return $next($request);
    }
}
