<?php

namespace Shopbox\Http\Controllers\Zpanel\POS;

use Illuminate\Support\Facades\DB;
use Shopbox\Transformers\ProductCollectionTransformer;
use Illuminate\Http\Request;
use Shopbox\Http\Controllers\Controller;

class PosController extends Controller
{
    public function index()
    {
        
    	if(auth()->user()->email === 'demo@aidantz.com') {
    		return view('zpanel.pos.index');
    	}

    	abort(404);
    }

    public function config()
    {
    	return view('zpanel.pos.config');
    }

    public function products(Request $request)
    {
        $records = DB::table('products')
            ->selectRaw('ANY_VALUE(products.id) as id, ANY_VALUE(stocks.product_attribute_id) as attribute_id, ANY_VALUE(stocks.id) as stock_id, ANY_VALUE(products.name) as name')
            ->leftJoin('product_attributes', 'products.id' , '=', 'product_attributes.product_id')
            ->leftJoin('stocks', 'products.id', '=', 'stocks.product_id')
            ->leftJoin('product_attribute_combinations', 'product_attribute_combinations.product_attribute_id', '=', 'product_attributes.id')
            ->where('products.store_id', $request->tenant()->id)
            ->where('products.state', 1)
            ->whereNull('products.deleted_at')
            ->groupBy('stocks.id')
            ->orderBy('stocks.product_id')
            ->get();


        $products = fractal()
                        ->collection($records)
                        ->transformWith(new ProductCollectionTransformer(39))
                        ->toArray();

        return response()->json($products);
    }

    public function categories(Request $request)
    {
        $categories = $request->tenant()->categories()->whereNotNull('parent_id')->get(['id', 'name']);

        return response()->json($categories);
    }

}
