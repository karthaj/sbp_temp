<?php

namespace Shopbox\Transformers\StoreFront;

use Carbon\Carbon;
use League\Fractal\TransformerAbstract;
use Modules\Product\Entities\Product;
use Shopbox\Models\Wishlist;


class ProductCollectionTransformer extends TransformerAbstract
{
	public function transform($data)
	{

		if($data instanceof Product) {
			$product = $data;
		} else if($data instanceof Wishlist) {
			$product = $data->product;
		} 

		return [
			'id' => $product->id,
			'type' => $product->type,
			'url' => route('stores.product.show', $product),
			'handle' => $product->slug,
			'name' => $product->name,
			'price' => (float) $product->selling_price,
			'special_price' => $this->getSpecialPrice($product),
			'price_min' => $product->variations->count() ? $product->variations->min('selling_price') > 0 ? (float) $product->variations->min('selling_price') : (float) $product->selling_price : (float) $product->selling_price,
			'price_max' => $product->variations->count() ? $product->variations->max('selling_price') > 0 ? (float) $product->variations->max('selling_price') : (float) $product->selling_price : (float) $product->selling_price,
			'cover_image' => $this->getProductCoverImage($product),
			'images' =>  fractal()
					            ->collection($product->images->where('cover', 0)->all())
					            ->transformWith(new ImageCollectionTransformer)
					            ->toArray()['data'],
			'in_stock' => $this->productInStock($product) > 0 ? true : false,
			'preorder' => (bool) $product->pre_order,
			'backorder' => (bool) $product->available_for_order
		];
	}

	protected function getSpecialPrice(Product $product)
	{
		$date = Carbon::now();
		$price = 0;
		$variant_price = 0;
		$product_attribute = '';
		$group = '';

		if($product->variations->count()) {
			$product_attribute = $product->variations->where('selling_price', (float) $product->variations->min('selling_price'))->first();
		}

		if(auth()->guard('web')->check() && auth()->user()->groups->contains('store_id', session('store')->id)) {
			$group = auth()->user()->groups->where('store_id', session('store')->id)->first();
		}

		if($product_attribute && $group) {

			if($product_attribute->selling_price > 0) {
				$variant_price = $product_attribute->selling_price;
			} else {
				$variant_price = $product->selling_price;
			}

			if($group->discounts->count() && $variant_price > 0) {
				foreach ($group->discounts as $reduction) {
					if($product_attribute->product->categories->contains('id', $reduction->category_id)) {
						$price = $variant_price - ($variant_price * $reduction->discount / 100);
						break;
					}
				}

				if(!$price && $group->discount > 0) {
					$price = $variant_price - ($variant_price * $reduction->discount / 100);
				}

			} else if($group->discount > 0 && $variant_price > 0) {
				$price = $variant_price - ($variant_price * $group->discount / 100);
			}

		} else if($product_attribute && $product_attribute->special_price > 0) {

			if($date->greaterThanOrEqualTo($product_attribute->special_active_on) && $date->lessThanOrEqualTo($product_attribute->special_end_on)) {
				$price = $product_attribute->special_price;
			}

		} else if($group && $product->selling_price > 0) {

			if($group->discounts->count()) {
				foreach ($group->discounts as $reduction) {
					if($product->categories->contains('id', $reduction->category_id)) {
						$price = $product->selling_price - ($product->selling_price * $reduction->discount / 100);
						break;
					}
				}

				if(!$price && $group->discount > 0) {
					$price = $product->selling_price - ($product->selling_price * $group->discount / 100);
				}

			} else if($group->discount > 0) { 
				$price = $product->selling_price - ($product->selling_price * $group->discount / 100);
			}
			
		} else if($product->special_price > 0) {

			if($date->greaterThanOrEqualTo($product->special_active_on) && $date->lessThanOrEqualTo($product->special_end_on)) {
				$price = $product->special_price;
			}

		}

		return (float) $price;

	}

	protected function getProductCoverImage(Product $product)
	{

		if(!$product->images->where('cover', 1)->count()) {
			return;
		}

		return fractal()
        ->item($product->images->where('cover', 1)->first())
        ->transformWith(new ImageCollectionTransformer)
        ->toArray()['data'];
	}


	protected function productInStock(Product $product) {
		// set_time_limit(0); 
		$instock = 0;

		if($product->variations->count()) {

			foreach($product->variations as $variation) {

				if($variation->stock && session('store')->onlineStore) {

					$stock = $variation->stock->storeStocks->where('store_location_id', session('store')->onlineStore->id)->first();
					
					if($stock && $stock->quantity) {

			    		$instock = $stock->quantity;
			    		break;

			   		} 

				}
				
			}

		} else if($product->stock && $product->stock->storeStocks->count() && session('store')->onlineStore) {

			$stock = $product->stock->storeStocks->where('store_location_id', session('store')->onlineStore->id)->first();

			if($stock) {

	    		$instock = $stock->quantity;

	   		} 

		}

		return $instock;

	}
	
}