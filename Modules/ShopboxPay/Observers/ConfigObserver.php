<?php

namespace Modules\ShopboxPay\Observers;

use Modules\ShopboxPay\Entities\Config;
use Jenssegers\Agent\Agent;
use Shopbox\Models\Zpanel\Track;

class ConfigObserver
{
	protected $agent;

	public function __construct(Agent $agent)
    {
        $this->agent = $agent;
    }
   
    public function creating(Config $config)
    {
        if(session()->has('store')) {
            $config->setAttribute('created_at_tz', $config->freshTimestamp()->timezone(session('store')->setting->timezone->timezone));
            $config->setAttribute('updated_at_tz', $config->freshTimestamp()->timezone(session('store')->setting->timezone->timezone));
        }
       
        if(auth()->check()) {
            $config->setAttribute('created_by', auth()->user()->email);
            $config->setAttribute('updated_by', auth()->user()->email);
        }
        
        $config->setAttribute('browser', $this->agent->browser());
        $config->setAttribute('ip_address', Track::getRealIpAddr());
        $config->setAttribute('platform', $this->agent->platform());
    }

    public function updating(Config $config)
    {
        $config->setAttribute('updated_at_tz', $config->freshTimestamp()->timezone(session('store')->setting->timezone->timezone));

        if(auth()->check()) {
           $config->setAttribute('updated_by', auth()->user()->email); 
        }
        
		$config->setAttribute('browser', $this->agent->browser());
        $config->setAttribute('ip_address', Track::getRealIpAddr());
        $config->setAttribute('platform', $this->agent->platform());
    }
}