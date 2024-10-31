<?php

namespace Shopbox\Http\Middleware\Tenant;

use Closure;

class AuthSubdomain
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
        $domain = $request->getHttpHost();
        $domain = explode('.', $domain);

        if(count($domain) == 3) {

          $current_domain = $domain[0];
          array_forget($domain, 0);
          if(implode($domain, '.') !== config('domain.app_domain')) {
            return abort('404');
          } 

        } else if(count($domain) == 2) {

          if(implode($domain, '.') !== config('domain.app_domain')) {
            return abort('404');
          }
          
        }

        if($request->has('ref')) {
          $source = cookie('source', $request->ref, 1440 * 30, '/', config('session.domain'));
          return $next($request)->cookie($source);
        }

        return $next($request);
    }
}
