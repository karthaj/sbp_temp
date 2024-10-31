<?php

namespace Shopbox\Http\Middleware\Tenant;

use Closure;

class CheckAccountLimits
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
        if($request->tenant()->plan->accounts_limit === $request->tenant()->users()->where('master', 0)->count()) {
            return redirect()->route('account.users');
        }

        return $next($request);
    }
}
