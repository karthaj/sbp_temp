<?php

namespace Shopbox\Http\Controllers\Tenant;

use Shopbox\Product;
use Illuminate\Http\Request;
use Shopbox\Http\Controllers\Controller;

class ProductController extends Controller
{
    public function store(Request $request)
    {
    	Product::create([
    		'name' => $request->name
    	]);

    	return back();
    }

    public function show(Product $product)
    {
    	return view('tenant.products.show', compact('product'));
    }

    public function destroy()
    {
    	Product::find(1)->delete();
    }
}
