<?php

namespace Shopbox\Transformers\Dropdown;

use League\Fractal\TransformerAbstract;
use Modules\Product\Entities\Category;


class CategoryTransformer extends TransformerAbstract
{
	public function transform(Category $category)
	{
		
		return [
			'id' => $category->id,
			'name' => $category->name,
			'handle' => $category->slug,
			'image' => $this->getCategoryImage($category)
			
		];
	}

	protected function getCategoryImage(Category $category)
	{

		if($category->thumb_image) {

			return url(getStoreUrl(session('store')).'/stores/'.session('store')->domain.'/category/'.$category->thumb_image);
		}

		return "";

	}
	
}