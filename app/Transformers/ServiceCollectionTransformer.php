<?php

namespace Shopbox\Transformers;

use League\Fractal\TransformerAbstract;
use Shopbox\Models\Zpanel\Service;


class ServiceCollectionTransformer extends TransformerAbstract
{
	public function transform(Service $service)
	{
		
		return [
			'id' => $service->id,
			'name' => $service->name,
			'recurring' => (bool) $service->recurring,
			'ends_at' => $service->store->expiry_date->toFormattedDateString(),
			'plugin' => $service->plugin ? true : false
		];
	}
	
}