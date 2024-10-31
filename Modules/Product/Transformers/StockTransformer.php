<?php

namespace Modules\Product\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\Product\Entities\Stock;
use Modules\Product\Entities\StoreLocation;
use Modules\Product\Traits\StockTrait;


class StockTransformer extends TransformerAbstract
{

	use StockTrait;

	protected $store;

	public function __construct($store = null)
	{
		$this->store = $store;
	}

	public function transform(Stock $stock)
	{
	
		return [	
			'stock_id' => $stock->id,
			'name' => $stock->product_attribute_id ? $this->getVariation($stock->productAttribute) : $stock->product->name,
			'image' => $this->getImage($stock),
			'sku' => $stock->product_attribute_id ? $stock->productAttribute->sku : $stock->product->sku,
			'stock' => $this->getStock($stock, $this->store),
			'price' => $this->getPrice($stock)
		];

	}

	protected function getStock(Stock $stock, StoreLocation $store = null)
	{
		$qty = $stock->available_quantity;

		if($store && $store->stocks()->where('stock_id', $stock->id)->count()) {

			$qty = $store->stocks()->where('stock_id', $stock->id)->first()->quantity;

		}

		return $qty;
	}

	protected function getPrice(Stock $stock)
	{
		$price = 0;

		if($stock->product_attribute_id) {
			$stock->productAttribute->selling_price;
		} else {
			$price = $stock->product->selling_price;
		}

		return $price;
	}
	
}