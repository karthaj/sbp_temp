<?php

namespace Shopbox\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Product\Entities\Product;

class SearchController extends Controller
{
    public function index(Request $request)
    {	
    	$products = Product::search($request->q)->where('store_id', $request->tenant()->id)->where('state', 1)->where('active', 1)->get();

    	return $products;
    }
}
