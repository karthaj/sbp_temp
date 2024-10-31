<?php

namespace Modules\Product\Http\Controllers\DataTable;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Modules\Product\Transformers\StoreStockCollectionTransformer;
use Modules\Product\Entities\Product;

class WarehouseStockController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()) {
            return abort('404');
        }

        $products =  DB::table('products')
            ->selectRaw('ANY_VALUE(products.id) as id, ANY_VALUE(stocks.product_attribute_id) as attribute_id, ANY_VALUE(stocks.id) as stock_id, ANY_VALUE(products.name) as name, ANY_VALUE(products.description) as description, ANY_VALUE(COALESCE(products.sku, product_attributes.sku)) as sku, ANY_VALUE(stocks.available_quantity) as qty')
            ->leftJoin('product_attributes', 'products.id' , '=', 'product_attributes.product_id')
            ->leftJoin('stocks', 'products.id', '=', 'stocks.product_id')
            ->leftJoin('product_attribute_combinations', 'product_attribute_combinations.product_attribute_id', '=', 'product_attributes.id')
            ->where('products.store_id', $request->tenant()->id)
            ->where('products.state', 1)
            ->whereNull('products.deleted_at');

        if(!empty($request->q)) {
            $products = $products->where('name', 'like', '%'.$request->q.'%')
                                ->orWhere('products.sku', 'like', '%'.$request->q.'%')
                                ->orWhere('products.barcode', 'like', '%'.$request->q.'%')
                                ->orWhere('product_attributes.sku', 'like', '%'.$request->q.'%')
                                ->orWhere('product_attributes.barcode', 'like', '%'.$request->q.'%');
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
