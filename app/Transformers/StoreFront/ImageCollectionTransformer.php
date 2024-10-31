<?php

namespace Shopbox\Transformers\StoreFront;

use Modules\Product\Entities\ProductImage;
use League\Fractal\TransformerAbstract;


class ImageCollectionTransformer extends TransformerAbstract
{

	public function transform(ProductImage $image)
	{
		
		return [
			'id' => $image->id,
			'standard' => $image->home_default ? url(getStoreUrl(session('store')).'/stores/'.session('store')->domain.'/product/'.$image->home_default) : '',
			'tiny' => $image->cart_default ? url(getStoreUrl(session('store')).'/stores/'.session('store')->domain.'/product/'.$image->cart_default) : '',
			'small' => $image->small_default ? url(getStoreUrl(session('store')).'/stores/'.session('store')->domain.'/product/'.$image->small_default) : '',
			'medium' => $image->medium_default ? url(getStoreUrl(session('store')).'/stores/'.session('store')->domain.'/product/'.$image->medium_default) : '',
			'large' => $image->large_default ? url(getStoreUrl(session('store')).'/stores/'.session('store')->domain.'/product/'.$image->large_default) : '',
			'alt_text' => $image->alt_text,
			'cover' => (bool) $image->cover
		];

	}

}