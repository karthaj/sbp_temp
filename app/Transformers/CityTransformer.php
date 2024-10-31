<?php

namespace Shopbox\Transformers;

use League\Fractal\TransformerAbstract;
use Shopbox\Models\Zpanel\City;


class CityTransformer extends TransformerAbstract
{
	public function transform(City $city)
	{
		
		return [
			'id' => $city->id,
			'name' => $city->city_name,
			'zip_code' => $city->zip_code
		];
	}
	
}