<?php

namespace Shopbox\Transformers;

use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductAttribute;
use Modules\Product\Entities\StoreLocation;
use League\Fractal\TransformerAbstract;


class ProductCollectionTransformer extends TransformerAbstract
{

	protected $store;

	public function __construct($store)
	{
		$this->store = $store;
	}

	public function transform($product)
	{
		
		return [
			'product_id' => $product->id,
			'attribute_id' => $product->attribute_id,
			'name' => $product->name,
			'sku' => $this->getSKU($product),
			'barcode' => $this->getBarcode($product),
			'stock' => $this->getQuantity($product),
			'variation' => $this->getVariation($product->attribute_id),
			'image' => $this->getImage($product),
			'price' => $this->getPrice($product),
			'categories' => $this->getCategories($product)
		];
	}

	protected function getCategories($product)
	{
		$product = Product::find($product->id);

		return $product->categories->pluck('id');
	}

	protected function getBarcode($product) 
	{
		$product_attribute = ProductAttribute::find($product->attribute_id);
		$product = Product::find($product->id);

		if($product_attribute && $product_attribute->barcode) {

			return $product_attribute->barcode;

		}

		return $product->barcode;
	}

	protected function getSKU($product) 
	{
		$product_attribute = ProductAttribute::find($product->attribute_id);
		$product = Product::find($product->id);

		if($product_attribute && $product_attribute->sku) {

			return $product_attribute->sku;

		}

		return $product->sku;
	}

	protected function getPrice($product)
	{
		$product_attribute = ProductAttribute::find($product->attribute_id);
		$product = Product::find($product->id);

		if($product_attribute && $product_attribute->selling_price > 0) {

			return $product_attribute->selling_price;

		}

		return $product->selling_price;
	}

	protected function getQuantity ($product) 
	{
		$product_attribute = ProductAttribute::find($product->attribute_id);
		$product = Product::find($product->id);
		
		if($this->store) {

			$store = session('store')->storeLocations()->where('id', $this->store)->first();

			if(!$store){
				return 0;
			}

			if($store->stocks->count()) {
				
				if($product_attribute && $store->stocks->contains('stock_id', $product_attribute->stock->id)) {

					return $store->stocks()->where('stock_id', $product_attribute->stock->id)->first()->quantity;

				} else if($store->stocks->contains('stock_id', $product->stock->id)) {

					return $store->stocks()->where('stock_id', $product->stock->id)->first()->quantity;

				}

			} else {
				return 0;
			}
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