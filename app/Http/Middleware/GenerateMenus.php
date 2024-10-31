<?php

namespace Shopbox\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Contracts\View\Factory as ViewFactory;
use Shopbox\Models\Zpanel\Plugin;
use Shopbox\Models\Zpanel\DashboardMenu;
use Shopbox\Modules\Entities\Order;

class GenerateMenus
{
    /**
     * The view factory implementation.
     *
     * @var \Illuminate\Contracts\View\Factory
     */
    protected $view;

    /**
     * Create a new error binder instance.
     *
     * @param  \Illuminate\Contracts\View\Factory  $view
     * @return void
     */
    public function __construct(ViewFactory $view)
    {
        $this->view = $view;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    { 
        if(!$request->is('theme/*/editor')) {
            $items = DashboardMenu::with(['submenus', 'menu'])->whereNull('parent_id')->orderBy('order', 'asc')->get();
            $current_date = Carbon::now();

            if($current_date->lessThanOrEqualTo($request->tenant()->expiry_date)) {
                $duration = $request->tenant()->expiry_date->addDays(1)->diffInDays(Carbon::now());
            } else {
                $duration = 0;
            }
           
            $avatar = asset('assets/img/avatar.jpg');
            $pending_orders = $request->tenant()->orders()->whereHas('state', function ($query) {
                $query->where('slug', 'pending');
            })->count();
           
            $this->view->share([
                'items' => $items,
                'duration' => $duration,
                'current_date' => $current_date,
                'avatar' => $avatar,
                'pending_orders' => $pending_orders
            ]);
        }
       
        return $next($request);
    }
}
