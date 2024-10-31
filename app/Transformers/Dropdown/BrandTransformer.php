<?php

namespace Shopbox\Transformers\Dropdown;

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
			'image' => $this->getBrandImage($brand)
			
		];
	}

	protected function getBrandImage(Brand $brand)
	{

		if($brand->small_default) {

			return url(getStoreUrl(session('store')).'/stores/'.session('store')->domain.'/brand/'.$brand->small_default);
		}

		return "";

	}
	
}