<?php

namespace Modules\Product\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\Product\Entities\Category;


class CategoryCollectionTransformer extends TransformerAbstract
{
	public function transform(Category $category)
	{
		
		return [
			'id' => $category->id,
			'slug' => $category->slug,
			'url' => route('stores.categories.category', $category),
			'name' => $category->name,
			'image' => $category->cover_image ? asset('stores').'/'.$category->store->domain.'/category/'.$category->cover_image: ''
		];
	}
	
}