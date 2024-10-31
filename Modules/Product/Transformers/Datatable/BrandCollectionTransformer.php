<?php

namespace Modules\Product\Transformers\Datatable;

use League\Fractal\TransformerAbstract;
use Modules\Product\Entities\Brand;


class BrandCollectionTransformer extends TransformerAbstract
{
	public function transform(Brand $brand)
	{
		return [
			'id' => $brand->id,
			'name' => $brand->name,
			'slug' => $brand->slug,
			'image' => $brand->small_default ? asset('stores').'/'.$brand->store->domain.'/brand/'.$brand->small_default : asset('assets/img/ProductDefault.gif'),
			'products' => $brand->products->count(),
			'edit' => route('brands.edit', $brand),
		];
	}
	
}