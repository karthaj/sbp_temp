<?php

namespace Shopbox\Transformers\StoreFront;

use Modules\Menu\Entities\Item;
use League\Fractal\TransformerAbstract;


class MenuTransformer extends TransformerAbstract
{

	public function transform(Item $menu)
	{
		return [
			'id' => $menu->id,
			'name' => $menu->name,
			'target' => $menu->target_tab ? '_blank' : '_self',
			'url' => $menu->custom ? $menu->url : url(getStoreUrl(session('store')).'/'.$menu->url),
			'sub' => fractal()
		            ->collection($menu->children)
		            ->transformWith(new $this)
		            ->toArray()['data']
		];

	}

}