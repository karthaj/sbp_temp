<?php

namespace Shopbox\Http\Controllers\StoreFront\Brand;

use Illuminate\Http\Request;
use Modules\Product\Entities\Brand;
use Shopbox\Transformers\StoreFront\BrandTransformer;
use Shopbox\Transformers\StoreFront\ProductTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Shopbox\Http\Controllers\Controller;

class BrandController extends Controller
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

    public function index()
    {
    	$brands = session('store')->brands()->paginate(12);

        return  fractal()
                ->collection($brands->getCollection())
                ->transformWith(new BrandTransformer)
                ->paginateWith(new IlluminatePaginatorAdapter($brands))
                ->toArray();
    }

    public function getProducts(Request $request, Brand $brand)
    {
    	$this->validate($request, [
			'sort' => 'required'
		]);
		
		$queryParts =  $this->resolveQueryparts($request->sort);
	    $products = $brand->products()->where('blocked', 0)->where('state', 1)->where('active', 1)->orderBy($queryParts['column'], $queryParts['order'])->paginate(12);

	    return  fractal()
	            ->collection($products->getCollection())
	            ->transformWith(new ProductTransformer)
                ->paginateWith(new IlluminatePaginatorAdapter($products))
	            ->toArray();
    }
}
