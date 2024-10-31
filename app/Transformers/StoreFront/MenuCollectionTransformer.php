<?php

namespace Shopbox\Transformers\StoreFront;

use Shopbox\Transformers\StoreFront\MenuTransformer;
use Modules\Menu\Entities\Menu;
use League\Fractal\TransformerAbstract;


class MenuCollectionTransformer extends TransformerAbstract
{

	public function transform(Menu $menu)
	{
		return [
			'links' => fractal()
		            ->collection($menu->items)
		            ->transformWith(new MenuTransformer)
		            ->toArray()['data']
		];

	}

}