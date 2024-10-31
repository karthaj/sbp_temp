<?php

namespace Shopbox\Transformers;

use Shopbox\Models\Zpanel\State;
use League\Fractal\TransformerAbstract;
use Shopbox\Transformers\CityTransformer;


class StateTransformer extends TransformerAbstract
{
	public function transform(State $state)
	{
		
		return [
			'id' => $state->id,
			'iso_code' => $state->iso_code,
			'name' => $state->name,
			'cities' => fractal()->collection($state->cities)->transformWith(new CityTransformer)->toArray()['data'],
		];
	}
	
}