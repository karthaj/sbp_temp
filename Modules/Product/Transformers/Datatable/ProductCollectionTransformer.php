<?php

namespace Modules\Product\Transformers\Datatable;

use League\Fractal\TransformerAbstract;
use Modules\Product\Entities\Product;


class ProductCollectionTransformer extends TransformerAbstract
{
	public function transform(Product $product)
	{
		
		return [
			'id' => $product->id,
			'type' => $product->type,
			'name' => $product->name,
			'price' => $product->store->setting->currency->iso_code.' '.number_format($product->selling_price, 2),
			'image' => $this->getImage($product),
			'active' => $product->active,
			'edit' => route('product.edit', $product),
			'view' => url(getStoreUrl($product->store).'/products/'.$product->slug),
			'duplicate' => route('product.duplicate', $product)
		];
	}

	protected function getImage(Product $product) 
	{
		if($product->images()->where('cover', 1)->count()) {
			return asset('stores').'/'.$product->store->domain.'/product/'.$product->images()->where('cover', 1)->first()->small_default;
		}

		return asset('assets/img/ProductDefault.gif');
	}
	
}