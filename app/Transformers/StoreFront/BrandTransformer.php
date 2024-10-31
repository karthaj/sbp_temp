<?php

namespace Shopbox\Transformers\StoreFront;

use League\Fractal\TransformerAbstract;
use Modules\Product\Entities\Brand;


class BrandTransformer extends TransformerAbstract
{
	public function transform(Brand $brand)
	{
		
		return [
			'id' => $brand->id,
			'name' => $brand->name,
			'handle' => $brand->slug,
			'url' => route('stores.brands.brand', $brand),
			'image' => [
				'small' => $brand->small_default ? asset('stores/'.$brand->store->domain.'/brand/'.$brand->small_default) : '',
				'medium' => $brand->medium_default ? asset('stores/'.$brand->store->domain.'/brand/'.$brand->medium_default) : '',
				'large' => $brand->large_default ? asset('stores/'.$brand->store->domain.'/brand/'.$brand->large_default) : '',
			]
		];
	}
	
}