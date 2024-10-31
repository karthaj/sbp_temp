<?php

namespace Shopbox\Http\Middleware\Store;

use Closure;
use Illuminate\Contracts\View\Factory as ViewFactory;


class GlobalVariables
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
        $store = session('store');

        $sections = $request->settings['sections'];
        $settings = $request->settings;
        $powered_by_link = 'Powered by ShopBox';

        $payments = getActiveStorePayments($store);

        $menus = $store->menus()->with(['items' => function ($query) {
                $query->whereNull('parent_id');
            }, 'items.children'])->where('active', 1)->get();

        $this->view->share([
            'sections' => $sections,
            'settings' => $settings,
            'store' => $store,
            'powered_by_link' => $powered_by_link,
            'payments' => $payments,
            'theme_path' => $request->themePath,
            'menus' => $menus,
        ]);

        return $next($request);
    }

}
