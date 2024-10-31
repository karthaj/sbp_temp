<?php

namespace Shopbox\Http\Controllers\Zpanel\Dropdown;

use Illuminate\Http\Request;
use Shopbox\Transformers\Dropdown\ProductTransformer;
use Modules\Product\Entities\Product;
use Shopbox\Http\Controllers\Controller;

class ProductController extends Controller
{

    public function index(Request $request)
    {   
        $products = $request->tenant()->products()->where('blocked', 0)->where('name', 'like', '%'.str_slug($request->q, '%').'%')->get();

    	return  fractal()
                ->collection($products)
                ->transformWith(new ProductTransformer)
                ->toArray()['data'];
    }

    public function show(Product $product)
    {
    	return  fractal()
                ->item($product)
                ->transformWith(new ProductTransformer)
                ->toArray()['data'];
    }

}
