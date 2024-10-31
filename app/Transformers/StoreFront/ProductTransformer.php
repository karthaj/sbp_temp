<?php

namespace Shopbox\Transformers\StoreFront;

use Carbon\Carbon;
use League\Fractal\TransformerAbstract;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductAttribute;
use Modules\Product\Entities\Combination;
use Shopbox\Transformers\StoreFront\CategoryCollectionTransformer;
use Shopbox\Transformers\StoreFront\ProductCollectionTransformer;
use Shopbox\Transformers\StoreFront\OptionsTransformer;
use Shopbox\Transformers\StoreFront\ImageCollectionTransformer;
use Shopbox\Transformers\StoreFront\VariantCollectionTransformer;


class ProductTransformer extends TransformerAbstract
{
	public function transform(Product $product)
	{
		
		return [
			'id' => $product->id,
			'name' => $product->name,
			'type' => $product->type,
			'handle' => $product->slug,
			'url' => getStoreUrl($product->store).'/products/'.$product->slug,
			'description' => $product->description,
			'short_description' => $product->short_description,
			'brand' => $product->brand ? $product->brand->name : '',
			'price' => (float) $product->selling_price,
			'special_price' => $this->getProductDiscountPrice($product),
			'price_min' => $this->getProductMinPrice($product),
			'price_max' => $this->getProductMaxPrice($product),
			'sku' => $product->sku,
			'barcode' => $product->barcode,
			'isbn' => $product->isbn,
			'upc' => $product->upc,
			'in_stock' => $this->productInStock($product) > 0 ? true : false,
			'stock' =>  $this->productInStock($product),
			'instock_label' => $product->available_now,
			'outofstock_label' => $product->available_later,
			'preorder' => (bool) $product->pre_order,
			'available_on' => $product->available_date ? 'Available on '.$product->available_date->toFormattedDateString() : '',
			'backorder' => (bool) $product->available_for_order,
			'tags' => $product->tags,
			'cover_image' => $this->getProductCoverImage($product),
			'images' =>  fractal()
					            ->collection($product->images->where('cover', 0)->all())
					            ->transformWith(new ImageCollectionTransformer)
					            ->toArray()['data'],
			'related_products' => fractal()
					            ->collection($product->relatedProducts)
					            ->transformWith(new ProductCollectionTransformer)
					            ->toArray()['data'],
			'variants' => fractal()
				            ->collection($product->variations->load('product'))
				            ->transformWith(new VariantCollectionTransformer())
				            ->toArray()['data'],
			'options' => fractal()
				            ->item($product)
				            ->transformWith(new OptionsTransformer())
				            ->toArray()['data']
		];
	}

	protected function getProductMinPrice(Product $product)
	{
		if($product->variations->count() && (float) $product->variations->min('selling_price') > 0) {
			return (float) $product->variations->min('selling_price');
		} else {
			return (float) $product->selling_price;
		}
	}

	protected function getProductMaxPrice(Product $product)
	{
		if($product->variations->count() && (float) $product->variations->max('selling_price') > 0) {
			return (float) $product->variations->max('selling_price');
		} else {
			return (float) $product->selling_price;
		}
	}

	protected function getProductDiscountPrice(Product $product)
	{
		if(auth()->guard('web')->check() && auth()->user()->groups->contains('store_id', session('store')->id)) {

			$group = auth()->user()->groups->where('store_id', session('store')->id)->first();
			if($group->discounts->count() && $product->selling_price > 0) {
				foreach ($group->discounts as $reduction) {
					if($product->categories->contains('id', $reduction->category_id)) {
						return (float) $product->selling_price - ($product->selling_price * $reduction->discount / 100);
					}
				}

				if($group && $group->discount > 0) { 
					return (float) $product->selling_price - ($product->selling_price * $group->discount / 100);
				}

			} else if($group && $group->discount > 0 && $product->selling_price > 0) { 
				return (float) $product->selling_price - ($product->selling_price * $group->discount / 100);
			}

		}

		if($product->special_price && $product->special_active_on && $product->special_end_on) {

			if(Carbon::now()->greaterThanOrEqualTo($product->special_active_on) && Carbon::now()->lessThanOrEqualTo($product->special_end_on)) {
				return (float) $product->special_price;
			}

		}

		return 0;
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

		} else if($product->stock && session('store')->onlineStore) {

			$stock = $product->stock->storeStocks->where('store_location_id', session('store')->onlineStore->id)->first();

			if($stock) {

	    		$instock = $stock->quantity;

	   		} 

		}
			
		return $instock;

	}
	
}