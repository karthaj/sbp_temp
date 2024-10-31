<?php

namespace Shopbox\Http\Controllers\StoreFront\Product;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductAttribute;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Shopbox\Transformers\StoreFront\ProductTransformer;
use Shopbox\Transformers\StoreFront\ProductCollectionTransformer;
use Shopbox\Transformers\StoreFront\OptionsTransformer;
use Shopbox\Http\Controllers\Controller;

class ProductController extends Controller
{
	// protected function resolveQueryparts($sort)
 //    {
 //        return array_get([
 //            'newest' => [
 //                'column' => 'created_at',
 //                'order' => 'desc'
 //            ],
 //            'pricedesc' => [
 //                'column' => 'selling_price',
 //                'order' => 'desc'
 //            ],
 //            'priceasc' => [
 //                'column' => 'selling_price',
 //                'order' => 'asc'
 //            ],
 //            'alphaasc' => [
 //                'column' => 'name',
 //                'order' => 'asc'
 //            ],
 //            'alphadesc' => [
 //                'column' => 'name',
 //                'order' => 'desc'
 //            ]
 //        ], $sort);
 //    }

	// public function index(Request $request)
	// {	
	// 	$this->validate($request, [
	// 		'sort' => 'required'
	// 	]);
		
	// 	$queryParts =  $this->resolveQueryparts($request->sort);
	//     $products = session('store')->products()->with(['variations.stock.storeStocks', 'images'])->where('blocked', 0)->where('active', 1)->orderBy($queryParts['column'], $queryParts['order'])->paginate(12);
       
	//     return  fractal()
	//             ->collection($products->getCollection())
	//             ->transformWith(new ProductCollectionTransformer)
 //                ->paginateWith(new IlluminatePaginatorAdapter($products))
	//             ->toArray();
	// }

    // public function bestSelling()
    // {
    //     $best_selling_product_collection =  DB::table('orders')->selectRaw('products.id, ANY_VALUE(products.slug), COUNT(order_details.product_id) as sales, ANY_VALUE(products.name), ANY_VALUE(products.selling_price), ANY_VALUE(product_images.medium_default) as image, ANY_VALUE(products.type)')
    //     ->join('order_states', 'order_states.id', '=', 'orders.current_state')
    //     ->where('order_states.slug', 'completed')->join('order_details', 'order_details.order_id', '=', 'orders.id')
    //     ->join('products', 'products.id', '=', 'order_details.product_id')
    //     ->join('product_images', 'product_images.product_id', '=', 'products.id')
    //     ->where('orders.store_id', session('store')->id)->groupBy('products.id')->get();

    //     return  fractal()
    //             ->collection($best_selling_product_collection)
    //             ->transformWith(new ProductCollectionTransformer)
    //             ->toJson();
    // }

    // public function featured()
    // {
    //     return  fractal()
    //             ->collection(session('store')->featuredProducts)
    //             ->transformWith(new ProductCollectionTransformer)
    //             ->toJson();
    // }

    public function show(Product $product)
    {
        return fractal()->item($product->load(['stock', 'variations.stock.storeStocks', 'variations.combinations.option.attribute', 'images', 'relatedProducts.variations.stock.storeStocks', 'relatedProducts.stock.storeStocks']))->transformWith(new ProductTransformer)->toArray();
    }

}
