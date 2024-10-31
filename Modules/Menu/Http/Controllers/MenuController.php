<?php

namespace Modules\Menu\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Menu\Http\Requests\Menu\MenuStoreRequest;
use Modules\Menu\Http\Requests\Menu\MenuUpdateRequest;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\Category;
use Modules\Page\Entities\Page;
use Modules\Menu\Entities\Menu;
use Modules\Menu\Entities\Item;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $menus = Menu::all();
        return view('menu::menus.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('menu::menus.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(MenuStoreRequest $request)
    {

        $menu = Menu::create([
                    'name' => $request->menu_name,
                    'slug' => str_slug($request->menu_name, '-'),
                ]);

        return redirect()->route('menus.edit', $menu);
    }

    protected function inactiveMenus()
    {
        $menus = Menu::all();
        foreach($menus as $menu) {
            $menu->update(['active' => 0]);
        }
    }

   
    public function nestItem(Request $request)
    {
        $items = json_decode($request->items, true);
        $this->updateMenuItems($items);
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(Menu $menu)
    {
        $products = Product::where('blocked', 0)->where('active', 1)->where('state', 1)->get();
        $categories = Category::where('is_root_category', 0)->where('status', 1)->get();
        $pages = Page::where('active', 1)->get();

        return view('menu::menus.edit', compact('menu', 'products', 'categories', 'pages'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(MenuUpdateRequest $request, Menu $menu)
    {
        $items = json_decode($request->menu_items, true);
        $this->refreshMenuGroups($menu);
        $this->updateMenuItems($items);

        if(count($request->menu_item_label)) {
            foreach ($request->menu_item_label as $key => $label) {
                $item = Item::find($key);
                $item->name = $label;
                $item->updated_at_tz = $item->freshTimestamp()->timezone($request->tenant()->setting->timezone->timezone);
                $item->save();
            }
        }
        
        if(count($request->menu_item_url)) {
            foreach ($request->menu_item_url as $key => $url) {
                $item = Item::find($key);
                $item->url = $url;
                $item->updated_at_tz = $item->freshTimestamp()->timezone($request->tenant()->setting->timezone->timezone);
                $item->save();
            }
        }


        $menu->update([
            'name' => $request->menu_name,
            'slug' => str_slug($request->menu_name, '-'),
            'active' => $request->active ? 1 : 0,
            'updated_at_tz' => $menu->freshTimestamp()->timezone($request->tenant()->setting->timezone->timezone),
        ]);

        return redirect()->route('menus.index')->withSuccess('Menu saved.');

        
    }

    protected function refreshMenuGroups(Menu $menu)
    {
        if($menu->items->count()) {
            foreach($menu->items as $item) {
                $item->parent_id = null;
                $item->save();
            }
        }
    }

    protected function updateMenuItems ($items, $parent = null) 
    {
        $order = 0;
        foreach($items as $item) {
            $menu_item = Item::find($item['id']);
            $menu_item->order = $order += 1;

            if($parent) {
                $menu_item->item()->associate($parent);
            }

            $menu_item->save();

            if(array_has($item, 'children')) {
               
                $this->updateMenuItems($item['children'], $menu_item->id);
            }
            
        }
    }

    public function storeItem(Request $request) 
    {
        $items = json_decode($request->menu_items, true);
        $this->updateMenuItems($items);
    }

    public function storeItems(Request $request, Menu $menu) 
    {
        $item = new Item;
        $item->menu()->associate($menu);

        if($request->type == 'page') {

            $page = Page::find($request->page);
            $item->name = $page->title;
            $item->url = 'pages/'.$page->slug;
            $item->type = 'page';

        } else if($request->type == 'category') {

            if($request->category === 'categories') {
                $item->name = 'all categories';
                $item->url = $request->category;
            } else {
                $category = $request->tenant()->categories()->where('id', $request->category)->first();
                $item->name = $category->name;
                $item->url = 'categories/'.$category->slug;
            }
            $item->type = 'category';

        } else if($request->type == 'product') {

            if($request->product === 'products') {
                $item->name = 'all products';
                $item->url = $request->product;
            } else {
                $product = $request->tenant()->products()->where('id', $request->product)->first();
                $item->name = $product->name;
                $item->url = 'products/'.$product->slug;
            }
            $item->type = 'product';

        } else if($request->type == 'custom') {

            $item->name = $request->label;
            $item->url = $request->protocol.$request->link_url;
            $item->custom = 1;
            $item->target_tab = $request->target_tab;
            $item->type = 'custom';

        }
        
        $item->order = $this->getMenuOrder($menu) + 1;
        $item->created_at_tz = $item->freshTimestamp()->timezone($request->tenant()->setting->timezone->timezone);
        $item->updated_at_tz = $item->freshTimestamp()->timezone($request->tenant()->setting->timezone->timezone);
        $item->save();

        return redirect()->route('menus.edit', $menu);

    }

    protected function getMenuOrder(Menu $menu)
    {
        return (int) $menu->items()->count();
    }

    public function destroyItem(Item $item)
    {
        $item->delete();

        return response()->json([
            'redirect_path' => route('menus.edit', $item->menu)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();

        return response()->json([
            'redirect_path' => route('menus.index')
        ]);
    }
}
