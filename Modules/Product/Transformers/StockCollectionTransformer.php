<?php

namespace Modules\Product\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductAttribute;
use Modules\Product\Entities\StoreLocation;


class StockCollectionTransformer extends TransformerAbstract
{
	protected $store;

	public function __construct($store = null)
	{
		$this->store = $store;
	}

	public function transform($product)
	{
	
		return [	
			'name' => $product->name,
			'attribute_id' => $product->attribute_id,
			'sku' => $product->sku,
			'qty' => $this->getQuantity($product),
			'variation' => $this->getVariation($product->attribute_id),
			'url' => route('product.stocks.view', $product->stock_id),
			'image' => $this->getImage($product),
		];
	}

	protected function getQuantity ($product) 
	{
		$product_attribute = ProductAttribute::find($product->attribute_id);
		$product = Product::find($product->id);

		$store = session('store')->storeLocations()->where('id', $this->store)->first();
		
		if($store) {

			if($store->stocks->count()) {
				
				if($product_attribute) {

					if($product_attribute->stock && $store->stocks->contains('stock_id', $product_attribute->stock->id)) {

						return $store->stocks()->where('stock_id', $product_attribute->stock->id)->first()->quantity;

					}

					return 0;

				} else if($product->stock && $store->stocks->contains('stock_id', $product->stock->id)) {

					return $store->stocks()->where('stock_id', $product->stock->id)->first()->quantity;

				} 

				return 0;

			} 

			return 0;
		}

		if($product_attribute && $product_attribute->stock) {

			return $product_attribute->stock->available_quantity;

		}  

		return $product->stock->available_quantity;
	}

	protected function getVariation ($attribute_id) 
	{
		$variation = '';

		if($attribute_id) {
			$product_attribute = ProductAttribute::find($attribute_id);

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