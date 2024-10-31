<?php

namespace Shopbox\Http\Middleware;

use Closure;
use Shopbox\Models\Zpanel\Permission;
use Shopbox\Models\Zpanel\PluginPlan;

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
        if(!$request->user()->can($permission)) {

            $permission = Permission::where('name', $permission)->first();

            if(!$permission) {
                abort(404);
            }

            if($permission->plugin_id) {

                $plans = PluginPlan::where('plugin_id', $permission->plugin_id)->get();

                if($plans->count()) {

                    $request->session()->flash('plans', $plans);

                    return abort(402);

                }
            }

            abort(401);
        }

        return $next($request);

    }

}
