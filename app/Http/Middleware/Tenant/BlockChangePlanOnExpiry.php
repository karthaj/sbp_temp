<?php

namespace Shopbox\Http\Middleware\Tenant;

use Closure;
use Carbon\Carbon;

class BlockChangePlanOnExpiry
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
        if($request->tenant()->plan->slug !== 'trial' && Carbon::now()->greaterThan($request->tenant()->expiry_date)) {
            abort(404);
        }

        return $next($request);
    }
}
