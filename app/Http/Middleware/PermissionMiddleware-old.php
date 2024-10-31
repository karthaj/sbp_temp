<?php

namespace Shopbox\Http\Middleware;

use Closure;
use Shopbox\Models\Zpanel\Permission;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $permission)
    {

        if($this->hasPermissionThroughPlan($request->tenant(), $permission)) {

            if (!$request->user()->can($permission)) {
                abort(401);
            }

        } else {

            $permission = Permission::where('name', $permission)->first();

            if(!$permission) {
                abort(404);
            }

            $plans = $permission->plans()->where('slug', '<>', 'trial')->get();

            $request->session()->flash('plans', $plans);

            return abort(402);

        }

        return $next($request);

    }

    protected function hasPermissionThroughPlan ($store, $permission) { 

        $permission = Permission::where('name', $permission)->first();

        if(!$permission) {
            abort(404);
        }

        return $permission->plans->contains('id', $store->plan->id);     

    }

}
