<?php


namespace Shopbox\Services\Geoplugin\Contracts;


interface GeoPluginContract
{
	public function locate($ip = null, $currency);
}