<?php

namespace Shopbox\Http\Middleware\Tenant;

use Closure;
use Carbon\Carbon;
use Leafo\ScssPhp\Compiler;
use Shopbox\Models\Zpanel\Store;
use Shopbox\Tenant\Manager;
use Illuminate\Contracts\View\Factory as ViewFactory;

class Tenant
{
    protected $view;

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
        $domain = $request->getHttpHost();
        $domain = explode('.', $domain);

        if(count($domain) == 3) {

          $current_domain = $domain[0];
          array_forget($domain, 0);
          if(implode($domain, '.') === config('domain.app_domain')) {
            $tenant = Store::where('domain', $current_domain)->first();
          } else {
            return abort('404');
          }

        } else {
            return abort('404');
        }

        if(!auth()->user()->stores->contains('id', $tenant->id)) {
            auth()->logout();
            return redirect('//'.config('domain.app_domain').'/merchant/login');
            // return redirect('http://myshopbox.lk/unauthorized');
        }

        $this->registerTenant($tenant);
        /*$orders = session('store')->orders()->with(['state' => function ($query) {
            $query->where('slug', '!=', 'completed');
        }])->get();*/

        $update = json_decode(file_get_contents(storage_path('app/appconfig/themes/'.$tenant->template->theme->slug.'/config/setting.json')), true);
        $theme_version = $update['version'];
        
        $this->view->share([
            'theme_version' => $theme_version,
        ]);

        return $next($request);
    }

    protected function registerTenant($tenant)
    {
        app(Manager::class)->setTenant($tenant);
        session()->put('tenant', $tenant->id);
        session()->put('store', $tenant);
    }

    protected function resolveTenant($id)
    {
        return  Store::find($id);
    }

}
