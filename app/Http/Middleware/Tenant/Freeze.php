<?php

namespace Shopbox\Http\Middleware\Tenant;

use Closure;

class Freeze
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
        $redirect = checkAccountStatus($request->tenant());

        if($redirect) {
            return $redirect;
        }

        return $next($request);
    }
}
