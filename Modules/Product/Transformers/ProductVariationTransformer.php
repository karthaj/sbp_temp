<?php

namespace Modules\Product\Transformers;

use Illuminate\Support\Facades\DB;
use League\Fractal\TransformerAbstract;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductAttribute;
use Modules\Product\Entities\Combination;


class ProductVariationTransformer extends TransformerAbstract
{
	public function transform($product_attribute)
	{
		
		return [
			'id' => $product_attribute->id,
			'price' => $product_attribute->getFormattedPrice($product_attribute->selling_price),
			'sku' => $product_attribute->sku,
			'barcode' => $product_attribute->barcode,
			'isbn' => $product_attribute->isbn,
			'upc' => $product_attribute->upc,
			'image' => $this->getImage($product_attribute), 
			'stock_count' => $this->getProductQuantity($product_attribute),
			'in_stock' => $this->getProductQuantity($product_attribute) > 0 ? true : false,
			'instock_label' => $product_attribute->product->available_now,
			'outofstock_label' => $product_attribute->product->available_later
		];
	}

	protected function getImage(ProductAttribute $product_attribute)
	{
		$image = '';

		if($product_attribute->image) {
			$image['id'] = $product_attribute->id;
	        $image['alt_text'] = $product_attribute->image->alt_text;
	        $image['cart_default'] = asset('stores').'/'.session('store')->domain.'/product/'.$product_attribute->image->cart_default;
	        $image['home_default'] = asset('stores').'/'.session('store')->domain.'/product/'.$product_attribute->image->home_default;
	        $image['small_default'] = asset('stores').'/'.session('store')->domain.'/product/'.$product_attribute->image->small_default;
	        $image['medium_default'] = asset('stores').'/'.session('store')->domain.'/product/'.$product_attribute->image->medium_default;
	        $image['large_default'] = asset('stores').'/'.session('store')->domain.'/product/'.$product_attribute->image->large_default;
		}
		

        return $image;
	}

	protected function getProductQuantity(ProductAttribute $product_attribute)
	{
		$stock = $product_attribute->stock->storeStocks()->where('store_location_id', session('store')->onlineStore->id)->first();
	
    	if($stock) {

    		return $stock->quantity;

   		} 

   		return 0;
	}
}