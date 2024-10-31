<?php

namespace Modules\Product\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\Product\Entities\Brand;


class BrandCollectionTransformer extends TransformerAbstract
{
	public function transform(Brand $brand)
	{
		
		return [
			'id' => $brand->id,
			'slug' => $brand->slug,
			'url' => route('stores.brands.brand', $brand),
			'name' => $brand->name,
			'image' => $brand->medium_default ? asset('stores').'/'.$brand->store->domain.'/brand/'.$brand->medium_default: ''
		];
	}
	
}