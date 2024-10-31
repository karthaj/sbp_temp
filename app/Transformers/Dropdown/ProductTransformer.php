<?php

namespace Shopbox\Transformers\Dropdown;

use League\Fractal\TransformerAbstract;
use Modules\Product\Entities\Product;


class ProductTransformer extends TransformerAbstract
{
	public function transform(Product $product)
	{
		
		return [
			'id' => $product->id,
			'name' => $product->name,
			'handle' => $product->slug,
			'image' => $this->getProductCoverImage($product)
			
		];
	}

	protected function getProductCoverImage(Product $product)
	{

		if($product->images()->where('cover', 1)->count()) {

			$image = $product->images()->where('cover', 1)->first()->small_default;

			return url(getStoreUrl(session('store')).'/stores/'.session('store')->domain.'/product/'.$image);
		}

		return "";

	}
	
}