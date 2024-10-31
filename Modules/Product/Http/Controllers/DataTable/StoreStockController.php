<?php

namespace Modules\Product\Http\Controllers\DataTable;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Modules\Product\Transformers\StoreStockCollectionTransformer;
use Modules\Product\Entities\Product;
use Illuminate\Support\Facades\DB;


class StoreStockController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()) {
            return abort('404');
        }

        $store = $request->tenant()->storeLocations()->where('online_sales', 1)->first();

        $products =  DB::table('products')
            ->selectRaw('ANY_VALUE(products.id) as id, ANY_VALUE(stocks.product_attribute_id) as attribute_id, ANY_VALUE(stocks.id) as stock_id, ANY_VALUE(products.name) as name, ANY_VALUE(products.description) as description, ANY_VALUE(store_stocks.quantity) as qty')
            ->join('stocks', 'products.id', '=', 'stocks.product_id')
            ->join('store_stocks', 'store_stocks.stock_id', '=', 'stocks.id')
            ->leftJoin('product_attributes', 'products.id' , '=', 'product_attributes.product_id')
            ->leftJoin('product_attribute_combinations', 'product_attribute_combinations.product_attribute_id', '=', 'product_attributes.id')
            ->where('store_stocks.store_location_id', $store->id)
            ->where('products.store_id', $request->tenant()->id)
            ->where('products.state', 1)
            ->whereNull('products.deleted_at');

        if(!empty($request->q)) {
            $products = $products->where(function ($query) use($request) {
                            $query->where('name', 'like', '%'.$request->q.'%')
                                    ->orWhere('products.sku', 'like', '%'.$request->q.'%')
                                    ->orWhere('products.barcode', 'like', '%'.$request->q.'%')
                                    ->orWhere('product_attributes.sku', 'like', '%'.$request->q.'%')
                                    ->orWhere('product_attributes.barcode', 'like', '%'.$request->q.'%');
                        });
        }

        $products = $products->groupBy('stocks.id')
                            ->orderBy('stocks.product_id')
                            ->paginate($request->limit);

        $product_collections = $products->getCollection();

        return  fractal()
                ->collection($product_collections)
                ->transformWith(new StoreStockCollectionTransformer)
                ->paginateWith(new IlluminatePaginatorAdapter($products))
                ->toArray();
    }

}
