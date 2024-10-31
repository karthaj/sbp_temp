<?php

namespace Modules\Product\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductAttribute;


class StoreStockCollectionTransformer extends TransformerAbstract
{

	public function transform($product)
	{
		return [
			'stock_id' => $product->stock_id,
			'name' => $product->name,
			'sku' => $this->getProductSKU($product->id, $product->attribute_id),
			'description' => $product->description,
			'qty' => $product->qty === null ? 0 : $product->qty,
			'variation' => $this->getVariation($product),
			'image' => $this->getImage($product),
		];
	}

	protected function getProductSKU($product_id, $product_attribute_id)
	{
		$product = Product::find($product_id);
		$product_attribute = ProductAttribute::find($product_attribute_id);
		$sku = $product->sku;

		if($product_attribute && $product_attribute->sku) {
			$sku = $product_attribute->sku;
		}

		return $sku;

	}

	protected function getVariation ($product) 
	{
		$variation = '';

		if($product->attribute_id) {
			$product_attribute = ProductAttribute::find($product->attribute_id);

			foreach ($product_attribute->combinations as $index => $combination) {
				$variation .= $combination->option->attribute->public_name.' - '.$combination->option->name;

				if($index < $product_attribute->combinations->count() - 1) {
					$variation .= ', ';
				}
			}
		}

		return $variation;
	}

	protected function getImage($product) 
	{
		if($product->attribute_id) {

			$product_attribute = ProductAttribute::find($product->attribute_id);

			if($product_attribute->image) {
				return asset('stores').'/'.session('store')->domain.'/product/'.$product_attribute->image->medium_default;
			}
		}

		$product = Product::find($product->id);
		
		if($product->image()) {
			return asset('stores').'/'.session('store')->domain.'/product/'.$product->image();
		}

		return asset('assets/img/ProductDefault.gif');
	
	}
	
}