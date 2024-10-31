<?php

namespace Shopbox\Transformers\Cart;

use League\Fractal\TransformerAbstract;
use Modules\Product\Entities\Cart;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\CartProduct;
use Shopbox\Transformers\Cart\ProductTransformer;


class CartTransformer extends TransformerAbstract
{

	public function transform($cart)
	{
		return [
			'id' => $cart->reference,
			'email' => $cart->customer ? $cart->customer->customerEmail : "",
			'currency' => $cart->currency->iso_code,
			'original_total_price' => $this->getCartTotal($cart),
			'total_price' => $this->getCartTotal($cart) - collect($this->getDiscounts($cart))->sum('amount'),
			'total_discount' => collect($this->getDiscounts($cart))->sum('amount'),
			'total_weight' => $this->getCartWeight($cart),
			'discounts' => $this->getDiscounts($cart),
			'cart_level_discounts' => [],
			'item_count' => $cart->items->sum('quantity'),
			'items' => fractal()->collection($cart->items)->transformWith(new ProductTransformer)->toArray()['data'],
			'need_shipping' => $cart->requiredShipping(),
			'requires_splitting' => $this->requiresSplitting($cart),
			'stock_reserved' => (bool) $cart->stock_reserved,
			'inventory_check' => $cart->checkInventory() 
		];
	}

	protected function getDiscounts(Cart $cart)
	{
		$discounts = [];
		$items = collect(fractal()->collection($cart->items)->transformWith(new ProductTransformer)->toArray()['data']);

		foreach ($cart->discounts as $key => $cart_discount) {
			$discounts[$key]['id'] = $cart_discount->discount->id;
			$discounts[$key]['code'] = $cart_discount->discount->code;
			$discounts[$key]['name'] = $cart_discount->discount->name;
			$discounts[$key]['amount'] = $items->sum('total_discount');
		}
		
		return $discounts;

	}

	protected function getCartTotal(Cart $cart)
	{
		$total = 0;
		$items = fractal()->collection($cart->items)->transformWith(new ProductTransformer)->toArray()['data'];

		foreach ($items as $item) {
			$total += $item['line_price'];
		}

		return (float) $total;
	}

	protected function getCartWeight(Cart $cart)
	{
		$weight = 0;
		$items = fractal()->collection($cart->items)->transformWith(new ProductTransformer)->toArray()['data'];

		foreach ($items as $item) {
			$weight += $item['weight'] * $item['quantity'];
		}

		return (float) $weight;
	}

	protected function requiresSplitting(Cart $cart)
	{	
		$items = fractal()->collection($cart->items)->transformWith(new ProductTransformer)->toArray()['data'];

		foreach ($items as $item) {
			if($item['requires_splitting']) {
				return true;
			}
		}

		return false;		
	}

}