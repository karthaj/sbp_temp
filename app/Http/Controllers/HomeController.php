<?php

namespace Shopbox\Http\Controllers;

use Shopbox\Models\Zpanel\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HomeController extends Controller
{
	public function __construct()
    {
        $this->middleware('auth:admin');
    }
	
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
	
        $stores = $request->user()->stores;

        return view('home', compact('stores'));
    }
    
}
