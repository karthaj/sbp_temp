<?php

namespace Shopbox\Http\Controllers\StoreFront\Menu;

use Illuminate\Http\Request;
use Modules\Menu\Entities\Menu;
use Shopbox\Transformers\StoreFront\MenuTransformer;
use Shopbox\Transformers\StoreFront\MenuCollectionTransformer;
use Shopbox\Http\Controllers\Controller;

class MenuController extends Controller
{
	public function index()
	{
		$menus = session('store')->menus()->where('active', 1)->get();
		return  fractal()
	            ->collection($menus)
	            ->transformWith(new MenuCollectionTransformer)
	            ->toArray();
	}

    public function show(Menu $menu)
    {
        return  fractal()
	            ->collection($menu->items()->whereNull('parent_id')->get())
	            ->transformWith(new MenuTransformer)
	            ->toArray();
    }
}
