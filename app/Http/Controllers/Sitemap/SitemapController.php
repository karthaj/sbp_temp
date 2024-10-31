<?php

namespace Shopbox\Http\Controllers\Sitemap;

use Illuminate\Http\Request;
use Shopbox\Http\Controllers\Controller;

class SitemapController extends Controller
{
    public function index()
    {
    	return response()->view('sitemap.index', [
	        'store' => session('store'),
	    ])->header('Content-Type', 'text/xml');
    }

    public function products()
    {
    	return response()->view('sitemap.products', [
	        'products' => session('store')->products->where('blocked', 0)->where('active', 1),
	    ])->header('Content-Type', 'text/xml');
    }

    public function pages()
    {
    	return response()->view('sitemap.pages', [
	        'pages' => session('store')->pages->where('active', 1),
	    ])->header('Content-Type', 'text/xml');
    }

    public function categories()
    {
    	return response()->view('sitemap.categories', [
	        'categories' => session('store')->categories->where('is_root_category', 0)->where('status', 1),
	    ])->header('Content-Type', 'text/xml');
    }

    public function blogs()
    {
    	return response()->view('sitemap.blogs', [
	        'blogs' => session('store')->blogs->where('active', 1),
	    ])->header('Content-Type', 'text/xml');
    }
}
