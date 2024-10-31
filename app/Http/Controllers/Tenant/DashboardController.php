<?php

namespace Shopbox\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use Shopbox\Http\Controllers\Controller;
use Shopbox\Store;
use Shopbox\Product;

class DashboardController extends Controller
{
    public function index()
    {
    	$products = Product::all();
    	//dd(request()->tenant());
    	return view('tenant.dashboard', compact('products'));
    }
}
