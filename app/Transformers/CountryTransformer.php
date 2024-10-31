<?php

namespace Shopbox\Transformers;

use Shopbox\Models\Zpanel\Country;
use League\Fractal\TransformerAbstract;
use Shopbox\Transformers\StateTransformer;


class CountryTransformer extends TransformerAbstract
{
	public function transform(Country $country)
	{
		
		return [
			'id' => $country->id,
			'iso_code' => $country->iso_code,
			'name' => $country->name,
			'need_zip_code' => (bool) $country->need_zip_code,
			'states' => fractal()->collection($country->states)->transformWith(new StateTransformer)->toArray()['data'],
		];
	}
	
}