<?php

namespace Shopbox\Transformers\Dropdown;

use League\Fractal\TransformerAbstract;
use Modules\Menu\Entities\Menu;


class MenuTransformer extends TransformerAbstract
{
	public function transform(Menu $menu)
	{
		
		return [
			'id' => $menu->id,
			'name' => $menu->name,
			'handle' => $menu->slug
			
		];
	}
	
}