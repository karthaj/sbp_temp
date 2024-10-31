<?php

namespace Shopbox\Http\Controllers\StoreFront\Category;

use Illuminate\Http\Request;
use Modules\Product\Entities\Category;
use Shopbox\Transformers\StoreFront\CategoryTransformer;
use Shopbox\Transformers\StoreFront\ProductTransformer;
use Shopbox\Transformers\StoreFront\CategoryCollectionTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Shopbox\Http\Controllers\Controller;

class CategoryController extends Controller
{
    protected function resolveQueryparts($sort)
    {
        return array_get([
            'newest' => [
                'column' => 'created_at',
                'order' => 'desc'
            ],
            'pricedesc' => [
                'column' => 'selling_price',
                'order' => 'desc'
            ],
            'priceasc' => [
                'column' => 'selling_price',
                'order' => 'asc'
            ],
            'alphaasc' => [
                'column' => 'name',
                'order' => 'asc'
            ],
            'alphadesc' => [
                'column' => 'name',
                'order' => 'desc'
            ]
        ], $sort);
    }

    public function index(Request $request)
    {

    	$categories = session('store')->categories()->where('is_root_category', 0)->where('status', 1)->paginate(12);

    	return  fractal()
	            ->collection($categories->getCollection())
	            ->transformWith(new CategoryCollectionTransformer)
                ->paginateWith(new IlluminatePaginatorAdapter($categories))
	            ->toArray();
    }

    public function show(Category $category)
    {
    	return  fractal()
	            ->item($category)
	            ->transformWith(new CategoryTransformer)
	            ->toArray();
    }

    public function getProducts(Request $request, Category $category)
    {
        $this->validate($request, [
            'sort' => 'required'
        ]);
        
        $queryParts =  $this->resolveQueryparts($request->sort);
        $products = $category->products()->where('blocked', 0)->where('state', 1)->where('active', 1)->orderBy($queryParts['column'], $queryParts['order'])->paginate(12);

        return  fractal()
                ->collection($products->getCollection())
                ->transformWith(new ProductTransformer)
                ->paginateWith(new IlluminatePaginatorAdapter($products))
                ->toArray();
    }

}
