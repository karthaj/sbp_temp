<?php

namespace Modules\Product\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\Product\Entities\ProductAttribute;


class ProductVariationCollectionTransformer extends TransformerAbstract
{

	public function transform(ProductAttribute $product_attribute)
	{
		
		return [	
			'id' => $product_attribute->id,
			'sku' => $product_attribute->sku,
			'barcode' => $product_attribute->barcode,
			'isbn' => $product_attribute->isbn,
			'upc' => $product_attribute->upc,
			'cost_price' => $product_attribute->cost_price,
			'selling_price' => $product_attribute->selling_price,
			'special_price' => $product_attribute->special_price,
			'special_active_date' => $product_attribute->special_active_on ? $product_attribute->special_active_on->toDateString() : '',
			'special_active_time' => $product_attribute->special_active_on ? $product_attribute->special_active_on->format('g:i A') : '',
			'special_end_date' => $product_attribute->special_active_on ? $product_attribute->special_end_on->toDateString() : '',
			'special_end_time' => $product_attribute->special_active_on ? $product_attribute->special_end_on->format('g:i A') : '',
			'weight' => $product_attribute->weight,
			'height' => $product_attribute->height,
			'width' => $product_attribute->width,
			'depth' => $product_attribute->depth,
			'variation' => $this->getVariation($product_attribute),
			'image' => $this->getImage($product_attribute),
		];
	}

	protected function getVariation (ProductAttribute $product_attribute) 
	{
		$variation = '';
	
		foreach ($product_attribute->combinations as $index => $combination) {
			$variation .= $combination->option->attribute->public_name.': '.$combination->option->name;

			if($index < $product_attribute->combinations->count() - 1) {
				$variation .= ', ';
			}
		}
	
		return $variation;
	}

	protected function getImage(ProductAttribute $product_attribute) 
	{
		if($product_attribute->image) {
			return asset('stores').'/'.session('store')->domain.'/product/'.$product_attribute->image->small_default;
		}
	
	}
	
}