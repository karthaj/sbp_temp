<?php

namespace Shopbox\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\Product\Entities\Product;


class BestSellingProductCollectionTransformer extends TransformerAbstract
{
	public function transform($data)
	{
		$product = Product::find($data->id);
		
		return [
			'id' => $product->id,
			'type' => $product->type,
			'url' => route('stores.product.show', $product),
			'name' => $product->name,
			'price' => $product->selling_price,
			'special_price' => $product->special_price ?: 0,
			'sales' => $data->sales,
			'images' => $product->getImages()
		];
	}
	
}