<?php

namespace Shopbox\Traits;

use Modules\Product\Entities\Cart;

trait Stock
{
	public function reserve(Cart $cart)
	{
		foreach ($cart->items as $item) {
			if($item->stock) {
				$item->stock->quantity -= $item->quantity;
				$item->stock->reserved += $item->quantity;
				$item->stock->save();
			}
		}
	}

	public function restock(Cart $cart)
	{
		foreach ($cart->items as $item) {
			if($item->stock) {
				$item->stock->quantity += $item->quantity;
				$item->stock->reserved -= $item->quantity;
				$item->stock->save();
			}		
		}
	}

}