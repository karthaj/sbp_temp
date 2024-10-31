<?php

namespace Modules\Product\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\Product\Entities\Product;


class ProductCollectionTransformer extends TransformerAbstract
{
	public function transform($product)
	{
		
		return [
			'id' => $product->id,
			'type' => $product->type,
			'url' => route('stores.product.show', $product),
			'name' => $product->name,
			'price' => $product->selling_price,
			'special_price' => $product->special_price ?: 0,
			'images' => $product->getImages()
		];
	}
	
}