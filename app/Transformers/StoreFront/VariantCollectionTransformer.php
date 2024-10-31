<?php

namespace Shopbox\Transformers\StoreFront;

use Carbon\Carbon;
use Modules\Product\Entities\ProductAttribute;
use League\Fractal\TransformerAbstract;


class VariantCollectionTransformer extends TransformerAbstract
{

	public function transform(ProductAttribute $variant)
	{
		
		return [
			'id' => $variant->id,
			'options' => $variant->combinations->pluck('option_id'),
			'sku' => $variant->sku ?: $variant->product->sku,
			'barcode' => $variant->barcode ?: $variant->product->barcode,
			'isbn' => $variant->isbn ?: $variant->product->isbn,
			'upc' => $variant->upc ?: $variant->product->ipc,
			'price' => (float) $variant->selling_price ?: (float) $variant->product->selling_price,
			'special_price' => $this->getVariantDiscountPrice($variant),
			'weight' => (float) $variant->weight ?: (float) $variant->product->weight,
			'stock' => $this->getStock($variant),
			'image' => $this->featuredImage($variant)
		];

	}

	protected function getVariantDiscountPrice(ProductAttribute $variant)
	{
		if(auth()->guard('web')->check() && auth()->user()->groups->contains('store_id', session('store')->id)) {
			$price = 0;
			$group = auth()->user()->groups->where('store_id', session('store')->id)->first();

			if($variant->selling_price > 0) {
				$price = $variant->selling_price;
			} else {
				$price = $variant->product->selling_price;
			}

			if($group->discounts->count() && $price > 0) {
				foreach ($group->discounts as $reduction) {
					if($variant->product->categories->contains('id', $reduction->category_id)) {
						return (float) $price - ($price * $reduction->discount / 100);
					}
				}

				if($group && $group->discount > 0) { 
					return (float) $price - ($price * $group->discount / 100);
				}

			} else if($group && $group->discount > 0 && $price > 0) { 
				return (float) $price - ($price * $group->discount / 100);
			}

		}

		if($variant->special_price && $variant->special_active_on && $variant->special_end_on) {
		
			if(Carbon::now()->greaterThanOrEqualTo($variant->special_active_on) && Carbon::now()->lessThanOrEqualTo($variant->special_end_on)) {
				return (float) $variant->special_price;
			}

		} else if($variant->product->special_price && $variant->product->special_active_on && $variant->product->special_end_on) {
			if(Carbon::now()->greaterThanOrEqualTo($variant->product->special_active_on) && Carbon::now()->lessThanOrEqualTo($variant->product->special_end_on)) {
				return (float) $variant->product->special_price;
			}
		}

		return 0;
	}

	protected function getStock(ProductAttribute $product_attribute)
	{
		$stock = '';
		if($product_attribute->stock && $product_attribute->stock->storeStocks) {
			$stock = $product_attribute->stock->storeStocks()->where('store_location_id', session('store')->onlineStore->id)->first();
		}
		
    	if($stock && $stock->quantity) {

    		return $stock->quantity;

   		} 

   		return 0;
	}

	protected function featuredImage(ProductAttribute $variant)
	{	
		if(!$variant->image) {
			return;
		}

		$image = fractal()
		        ->item($variant->image)
		        ->transformWith(new ImageCollectionTransformer)
		        ->toArray();

		return $image['data'];
	}

	protected function checkAvailability(ProductAttribute $product_attribute)
	{
		$stock = $product_attribute->stock->storeStocks()->where('store_location_id', session('store')->onlineStore->id)->first();
	
    	if($stock && $stock->quantity) {

    		return true;

   		} 

   		return false;
	}

}