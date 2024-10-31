<?php

namespace Shopbox\Http\Controllers\Zpanel\Dropdown;

use Illuminate\Http\Request;
use Shopbox\Transformers\Dropdown\MenuTransformer;
use Modules\Menu\Entities\Menu;
use Shopbox\Http\Controllers\Controller;

class MenuController extends Controller
{

    public function index(Request $request)
    {   
        $menus = $request->tenant()->menus()->where('active', 1)->where('name', 'like', '%'.$request->q.'%')->get();

    	return  fractal()
                ->collection($menus)
                ->transformWith(new MenuTransformer)
                ->toArray()['data'];
    }

    public function show(Menu $menu)
    {
    	return  fractal()
                ->item($menu)
                ->transformWith(new MenuTransformer)
                ->toArray()['data'];
    }

}