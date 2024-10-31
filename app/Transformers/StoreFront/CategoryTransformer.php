<?php

namespace Shopbox\Transformers\StoreFront;

use Modules\Product\Entities\Category;
use League\Fractal\TransformerAbstract;


class CategoryTransformer extends TransformerAbstract
{

	public function transform(Category $category)
	{
		
		return [
			'id' => $category->id,
			'handle' => $category->slug,
			'name' => $category->name,
			'description' => html_entity_decode($category->description),
			'image' => [
				'cover' => $category->cover_image ? asset('stores').'/'.session('store')->domain.'/category/'.$category->cover_image : '',
				'thumbnail' => $category->thumb_image ? asset('stores').'/'.session('store')->domain.'/category/'.$category->thumb_image : ''
			]
		];

	}
}

	