<?php

namespace Shopbox\Http\Controllers\Zpanel\Dropdown;

use Illuminate\Http\Request;
use Modules\Product\Entities\Brand;
use Shopbox\Transformers\Dropdown\BrandTransformer;
use Shopbox\Http\Controllers\Controller;

class BrandController extends Controller
{

    public function index(Request $request)
    {   
        $brands = $request->tenant()->brands;

    	return  fractal()
                ->collection($brands)
                ->transformWith(new BrandTransformer)
                ->toArray()['data'];
    }

    public function show(Brand $brand)
    {
    	return  fractal()
                ->item($brand)
                ->transformWith(new BrandTransformer)
                ->toArray()['data'];
    }

}
