<?php

namespace Modules\Product\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\Product\Entities\StockTransfer;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductAttribute;

class StockTransferCollectionTransformer extends TransformerAbstract
{
	
	public function transform($transfer)
	{

		return [
			'name' => $transfer->stock->product->name,
			'variation' => $this->getVariation($transfer->stock->product_attribute_id),
			'image' => $this->getImage($transfer->stock->product, $transfer->stock->productAttribute),	
			'entity' => $transfer->store_location ? $transfer->store_location->name : 'Master',
			'user' => $transfer->employee ?: 'n/a',
			'remark' => $transfer->reason->name,
			'created_at' => $transfer->created_at->toDayDateTimeString(), 
			'qty' => $transfer->quantity,
			'sign' => $transfer->sign
			
		];
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

	protected function getImage(Product $product, ProductAttribute $product_attribute = null) 
	{
		if($product_attribute) {

			if($product_attribute->image) {
				return asset('stores').'/'.session('store')->domain.'/product/'.$product_attribute->image->medium_default;
			}
		}

		if($product->image()) {
			return asset('stores').'/'.session('store')->domain.'/product/'.$product->image();
		}

		return asset('assets/img/ProductDefault.gif');
	
	}
	
}