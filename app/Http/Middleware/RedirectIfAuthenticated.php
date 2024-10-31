<?php

namespace Shopbox\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        switch ($guard) {
            case 'admin':
              if (Auth::guard($guard)->check()) {
                  if(session()->has('store')) {
                    return redirect('//'.session('store')->domain.'.'.config('domain.app_domain').'/merchant/dashboard');
                  }
                  else {
                    return redirect('/merchant/manage/store');
                  }
              }
              break;
            default:
              if (Auth::guard($guard)->check()) {
                  return redirect()->back();
              }
              break;
        }

        return $next($request);
    }
}
