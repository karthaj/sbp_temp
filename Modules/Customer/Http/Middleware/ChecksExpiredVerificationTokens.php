<?php

namespace Modules\Customer\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ChecksExpiredVerificationTokens
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $redirect)
    {
        
        if ($request->verification_token->hasExpired()) {

            return redirect($redirect)->withError('Token expired.');

        }

        if($request->verification_token->customer->hasActivated()) {
            return redirect($redirect)->withError('Access denied.');
        }

        return $next($request);
    }
}
