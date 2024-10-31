<?php

namespace Shopbox\Http\Middleware;

use Closure;

class ChecksExpiredConfirmationTokens
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $redirect)
    {
       
        if ($request->confirmation_token->hasExpired()) {

            return redirect($redirect)->withError('Token expired.');

        } else if($request->confirmation_token->user->active) {

            return redirect($redirect)->withError('Already verified.');
        }

        return $next($request);
    }
}
