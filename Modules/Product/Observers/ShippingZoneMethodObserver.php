<?php

namespace Modules\Product\Observers;

use Modules\Product\Entities\ShippingZoneMethod;
use Jenssegers\Agent\Agent;
use Shopbox\Models\Zpanel\Track;

class ShippingZoneMethodObserver
{
	protected $agent;

	public function __construct(Agent $agent)
    {
        $this->agent = $agent;
    }
   
    public function creating(ShippingZoneMethod $shipping_zone_method)
    {
        $shipping_zone_method->setAttribute('created_at_tz', $shipping_zone_method->freshTimestamp()->timezone(session('store')->setting->timezone->timezone));
        $shipping_zone_method->setAttribute('updated_at_tz', $shipping_zone_method->freshTimestamp()->timezone(session('store')->setting->timezone->timezone));
        $shipping_zone_method->setAttribute('created_by', auth()->user()->email);
        $shipping_zone_method->setAttribute('updated_by', auth()->user()->email);
        $shipping_zone_method->setAttribute('browser', $this->agent->browser());
        $shipping_zone_method->setAttribute('ip_address', Track::getRealIpAddr());
        $shipping_zone_method->setAttribute('platform', $this->agent->platform());
    }

    public function updating(ShippingZoneMethod $shipping_zone_method)
    {
        $shipping_zone_method->setAttribute('updated_at_tz', $shipping_zone_method->freshTimestamp()->timezone(session('store')->setting->timezone->timezone));
        $shipping_zone_method->setAttribute('updated_by', auth()->user()->email);
		$shipping_zone_method->setAttribute('browser', $this->agent->browser());
        $shipping_zone_method->setAttribute('ip_address', Track::getRealIpAddr());
        $shipping_zone_method->setAttribute('platform', $this->agent->platform());
    }
}