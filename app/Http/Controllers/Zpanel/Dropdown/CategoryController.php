<?php

namespace Shopbox\Http\Controllers\Zpanel\Dropdown;

use Illuminate\Http\Request;
use Shopbox\Transformers\Dropdown\CategoryTransformer;
use Modules\Product\Entities\Category;
use Shopbox\Http\Controllers\Controller;

class CategoryController extends Controller
{

    public function index(Request $request)
    {   
        $categories = $request->tenant()->categories()->where('is_root_category', 0)->where('status', 1)->where('name', 'like', '%'.$request->q.'%')->get();

    	return  fractal()
                ->collection($categories)
                ->transformWith(new CategoryTransformer)
                ->toArray()['data'];
    }

    public function show(Category $category)
    {
    	return  fractal()
                ->item($category)
                ->transformWith(new CategoryTransformer)
                ->toArray()['data'];
    }

}