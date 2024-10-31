<?php

namespace Shopbox\Transformers\Cart;

use League\Fractal\TransformerAbstract;
use Modules\Product\Entities\Cart;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\CartProduct;
use Shopbox\Transformers\Cart\ProductTransformer;


class AbandonedCartTransformer extends TransformerAbstract
{

	public function transform($cart)
	{
		$cart = session('store')->carts()->where('id', $cart->id)->first();

		if(!$cart) {
			return [];
		}

		return [
			'id' => $cart->id,
			'reference' => $cart->reference,
			'url' => route('orders.abandoned.carts.edit', $cart),
			'date' => $cart->created_at_tz->toDateTimeString(),
			'customer' => $cart->customer ? $cart->customer->firstname && $cart->customer->lastname ? $cart->customer->firstname.' '.$cart->customer->lastname : 'No customer' : 'No customer',
			'total' => $cart->currency->iso_code.' '.number_format($this->getCartTotal($cart), 2),
			'stock_reserved' => (bool) $cart->stock_reserved,
			'restock_url' => route('orders.abandoned.cart.restock', $cart) 
		];
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

}