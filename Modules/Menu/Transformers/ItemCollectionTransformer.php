<?php

namespace Modules\Menu\Transformers;

use League\Fractal\TransformerAbstract;
use Modules\Menu\Entities\Menu;


class ItemCollectionTransformer extends TransformerAbstract
{
	public function transform(Menu $menu)
	{
		return [
			'items' => $this->getItems($menu)
		];
	}

	protected function getItems($menu)
	{
		$data = [];
		
		if($menu->items->count()) {
			$items = $menu->items()->whereNull('parent_id')->orderBy('order', 'asc')->get();

            foreach($items as $key => $item) {
               	$data[$key]['id'] = $item->id;
				$data[$key]['name'] = $item->name;
				$data[$key]['url'] = $item->custom ? $item->url : url($item->url);
				$data[$key]['target_tab'] = $item->target_tab ? '_blank' : '_self';

				if($item->children->count()) {
					$data[$key]['items'] = $this->getSubItems($item);
				}
            }

        }


		return $data;
		
	}

	protected function getSubItems($item) {

		$data = [];

		$items = $item->children()->orderBy('order', 'asc')->get();

		foreach ($items as $key => $item) {
			$data[$key]['id'] = $item->id;
			$data[$key]['name'] = $item->name;
			$data[$key]['url'] = $item->custom ? $item->url : url($item->url);
			$data[$key]['target_tab'] = $item->target_tab ? '_blank' : '_self';

			if($item->children->count()) {
				$data[$key]['items'] = $this->getSubItems($item);
			}
		}

		return $data;

	}
	
}