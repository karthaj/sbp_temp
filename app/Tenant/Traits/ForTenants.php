<?php

namespace Shopbox\Tenant\Traits;
use Shopbox\Tenant\Manager;
use Shopbox\Tenant\Scopes\TenantScope;
use Shopbox\Tenant\Observers\TenantObserver;

trait ForTenants
{

	public static function boot()
	{
		parent::boot();

		$manager = app(Manager::class);

		if($manager->getTenant() !== null) {
			
			static::addGlobalScope(
				new TenantScope($manager->getTenant())
			);
		
			static::observe(
				app(TenantObserver::class)
			);
		}

		
	}

}