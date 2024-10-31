<?php

namespace Shopbox\Http\Controllers\StoreFront\Blog;

use Illuminate\Http\Request;
use Shopbox\Transformers\StoreFront\BlogTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Shopbox\Http\Controllers\Controller;

class BlogController extends Controller
{
	
    public function index()
    {
    	$blogs = session('store')->blogs()->where('active', 1)->latest()->paginate(12);

        return  fractal()
                ->collection($blogs->getCollection())
                ->transformWith(new BlogTransformer)
                ->paginateWith(new IlluminatePaginatorAdapter($blogs))
                ->toArray();
    }

}
