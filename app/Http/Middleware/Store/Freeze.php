<?php

namespace Shopbox\Http\Middleware\Store;

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
        if(session('store')->suspended || session('store')->active != 1) {
            abort(555);
        }

        return $next($request);
    }
}
