<?php

namespace Shopbox\Services\Geoplugin;

use Shopbox\Services\GeoPlugin\geoPlugin;
use Shopbox\Services\Geoplugin\Contracts\GeoPluginContract;

class GeoPluginWrapper implements GeoPluginContract
{
	protected $geoplugin;

	public function __construct(geoPlugin $geoplugin)
	{
		$this->geoplugin = $geoplugin;
	}

	public function locate($ip = null, $currency)
	{
		$this->geoplugin->currency = $currency;
		$this->geoplugin->locate($ip);
		return $this->geoplugin;
	}
}