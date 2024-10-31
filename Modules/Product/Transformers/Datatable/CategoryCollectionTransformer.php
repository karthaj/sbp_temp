<?php

namespace Modules\Product\Transformers\Datatable;

use League\Fractal\TransformerAbstract;
use Modules\Product\Entities\Category;


class CategoryCollectionTransformer extends TransformerAbstract
{
	public function transform(Category $category)
	{
		return [
			'id' => $category->id,
			'name' => $category->name,
			'slug' => $category->slug,
			'products' => $category->products->count(),
			'status' => $category->status,
			'edit' => route('categories.edit', $category),
			'view' => url(getStoreUrl(session('store')).'/categories/'.$category->slug),
			'children' => fractal()
                        ->collection($category->children)
                        ->transformWith(new $this)
                        ->toArray()['data']
		];
	}
	
}