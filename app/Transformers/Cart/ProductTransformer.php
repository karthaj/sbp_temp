<?php

namespace Shopbox\Transformers\Cart;

use Carbon\Carbon;
use Modules\Product\Entities\Product;
use League\Fractal\TransformerAbstract;
use Modules\Discount\Entities\Discount;
use Modules\Product\Entities\CartProduct;
use Shopbox\Transformers\Cart\DiscountTransformer;


class ProductTransformer extends TransformerAbstract
{

	public function transform(CartProduct $item)
	{
		return [
			'id' => $item->id,
			'product_id' => $item->product_id,
			'variant_id' => $item->product_attribute_id,
			'quantity' => $item->quantity,
			'name' => $this->getProductName($item),
			'sku' => $this->getItemSKU($item),
			'url' => getStoreUrl(session('store')).'/products/'.$item->product->slug,
			'image' => $this->getProductImage($item),
			"selling_price" => $this->getSalePrice($item) ?: $this->getItemPrice($item),
		    "sale_price" => $this->getSalePrice($item),
		    "line_price" => $this->getLinePrice($item),
		    "total_discount" => $this->getTotalDiscount($item),
		    "discounts" => $this->getDiscounts($item),
		    "weight"=> $this->getItemWeight($item),
			'variant' => $this->getVariantName($item),
			'preorder' => (bool) $item->product->pre_order,
			'available_on' => $item->product->available_date ? 'Available on '.$item->product->available_date->toFormattedDateString() : '',
			'backorder' => (bool) $item->product->available_for_order,
			'stock_count' => $this->productInStock($item),
			'need_shipping' => $item->product->type != 'virtual' ? true : false,
			'requires_splitting' => $this->requiresSplitting($item)
		];
	}

	protected function requiresSplitting(CartProduct $item)
	{

		$cart_items = $item->cart->items;
		$shipping_classes = collect([]);
		$shipping_class = 0;

		foreach($cart_items as $cart_item) {
			if($cart_item->product->shipping_class_id) {
				$shipping_classes->push($cart_item->product->shipping_class_id);
			}
		}

		if($shipping_classes->count()) {
			$shipping_class = head($shipping_classes->sort()->values()->mode());
		}

		if((int) $item->product->shipping_class_id === $shipping_class) {
			return false;
		}

		return true;
		
	}

	protected function getTotalDiscount(CartProduct $item)
	{
		$discounts = collect($this->getDiscounts($item));

		return $discounts->sum('amount');
		
	}

	protected function getDiscounts(CartProduct $item)
	{
		$discounts = [];

		if($item->cart->discounts->count()) {

			foreach($item->cart->discounts as $key => $cart_discount) {
				
				if($cart_discount->discount->entire_order) {

					$discounts[$key]['id'] = $cart_discount->discount->id;
					$discounts[$key]['amount'] = $this->getDiscountAmount($cart_discount->discount, $this->getLinePrice($item));

				} elseif($cart_discount->discount->category && $item->product->categories->contains('id', ($cart_discount->discount->category_id))) {

					$discounts[$key]['id'] = $cart_discount->discount->id;
					$discounts[$key]['amount'] = $this->getDiscountAmount($cart_discount->discount, $this->getLinePrice($item));

				} elseif($cart_discount->discount->product && $item->product_id === $cart_discount->discount->product_id) {

					$discounts[$key]['id'] = $cart_discount->discount->id;
					$discounts[$key]['amount'] = $this->getDiscountAmount($cart_discount->discount, $this->getLinePrice($item));
				}

			}

		}

		return $discounts;
	}

	protected function getDiscountAmount(Discount $discount, $amount)
	{
		$discount_amount = 0;
		
		if($discount->reduction_percent != 0 && $discount->reduction_amount == 0) {
            $discount_amount +=  $amount / 100 * $discount->reduction_percent;
        } else if($discount->reduction_amount != 0 && $discount->reduction_percent == 0) {
        	if($discount->reduction_amount < $amount) {
        		$discount_amount += $discount->reduction_amount;
        	} else {
        		$discount_amount += $amount;
        	}
        }

        return $discount_amount;
	}

	protected function productInStock(CartProduct $item) {
		$stock = 0;

		if($item->product_attribute) {
			$stock = $item->product_attribute->stock->storeStocks->where('store_location_id', session('store')->onlineStore->id)->first();
		} else {
			$stock = $item->product->stock->storeStocks->where('store_location_id', session('store')->onlineStore->id)->first();
		}

		
		if($stock) {

    		return $stock->quantity;

   		} 

		return 0;
		 
	}

	protected function getItemWeight(CartProduct $item)
	{
		$weight = (float) $item->product->weight;

		if($item->product_attribute_id && $item->product_attribute && $item->product_attribute->weight > 0) {
			$weight = (float) $item->product_attribute->weight;
		}

		return (float) $this->convertWeightToGrams($weight);
	}

	protected function convertWeightToGrams($weight)
	{
		$store_weight_unit = session('store')->setting->weight->weight_code;
		$grams = 0;

		if($store_weight_unit === 'kg') {

			$grams = $weight * 1000;

		} else if($store_weight_unit === 'g') {
 			
			$grams = $weight;

		} else if($store_weight_unit === 'lb') {

			$grams = $weight * 453.592;

		} else if($store_weight_unit === 'oz') {

			$grams = $weight * 28.35;

		} else if($store_weight_unit === 't') {

			$grams = $weight * 907185;

		} 

		return (float) $grams;
	}

	protected function getProductName(CartProduct $item)
	{
		$name = $item->product->name;

		$variant_name = $this->getVariantName($item);

		if($variant_name) {
			return $name.' - '.$variant_name;
		}

		return $name;
	}

	protected function getVariantName(CartProduct $item)
	{
		$variant = '';

		if($item->product_attribute_id && $item->product_attribute) {

			foreach ($item->product_attribute->combinations as $key => $combination) {

				if(!$variant) {
					$variant .= $combination->option->name;
				} else {
					$variant .= ' / '. $combination->option->name;
				}
				
			}

		}

		return $variant;
	}

	protected function getItemPrice(CartProduct $item)
	{
		$price = $item->product->selling_price;

		if($item->product_attribute && $item->product_attribute->selling_price > 0) {
			$price = $item->product_attribute->selling_price;
		}

		return (float) $price;
	}

	protected function getLinePrice(CartProduct $item)
	{
		$discount_price = $this->getSalePrice($item);

		if($discount_price > 0) {
			return $item->quantity * $discount_price;
		}

		return $item->quantity * $this->getItemPrice($item);

	}

	protected function getSalePrice(CartProduct $item)
	{
		$date = Carbon::now();
		$price = 0;
		$group = '';
		$variant_price = 0;

		if(auth()->check() && auth()->user()->groups && auth()->user()->groups->contains('store_id', session('store')->id)) {
			$group = auth()->user()->groups->where('store_id', session('store')->id)->first();
		}

		if($item->product_attribute && $group) {

			if($$item->product_attribute->selling_price > 0) {
				$variant_price = $item->product_attribute->selling_price;
			} else {
				$variant_price = $item->product->selling_price;
			}

			if($group->discounts->count() && $variant_price > 0) {
				foreach ($group->discounts as $reduction) {
					if($item->product_attribute->product->categories->contains('id', $reduction->category_id)) {
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

		} else if($item->product_attribute && $item->product_attribute->special_price > 0) {

			if($date->greaterThanOrEqualTo($item->product_attribute->special_active_on) && $date->lessThanOrEqualTo($item->product_attribute->special_end_on)) {
				$price = $item->product_attribute->special_price;
			}

		} else if($group && $item->product->selling_price > 0) {

			if($group->discounts->count()) {
				foreach ($group->discounts as $reduction) {
					if($item->product->categories->contains('id', $reduction->category_id)) {
						$price = $item->product->selling_price - ($item->product->selling_price * $reduction->discount / 100);
						break;
					}
				}

				if(!$price && $group->discount > 0) {
					$price = $item->product->selling_price - ($item->product->selling_price * $group->discount / 100);
				}

			} else if($group->discount > 0) { 
				$price = $item->product->selling_price - ($item->product->selling_price * $group->discount / 100);
			}
			
		} else if($item->product->special_price > 0) {

			if($date->greaterThanOrEqualTo($item->product->special_active_on) && $date->lessThanOrEqualTo($item->product->special_end_on)) {
				$price = $item->product->special_price;
			}

		}

		return (float) $price;

	}

	protected function getItemSKU(CartProduct $item)
	{
		$sku = $item->product->sku;

		if($item->product_attribute_id && $item->product_attribute->sku) {
			$sku = $item->product_attribute->sku;
		}

		return $sku;
	}

	protected function getProductImage(CartProduct $item)
	{

		if($item->product_attribute && $item->product_attribute->image) {
			return url(getStoreUrl(session('store')).'/stores/'.session('store')->domain.'/product/'. $item->product_attribute->image->small_default);
		}

		if($item->product->images->where('cover', 1)->count()) {

			$image = $item->product->images->where('cover', 1)->first()->small_default;

			return url(getStoreUrl(session('store')).'/stores/'.session('store')->domain.'/product/'.$image);
		}

		return url(getStoreUrl(session('store')).'/assets/img/ProductDefault.gif');

	}
	
}