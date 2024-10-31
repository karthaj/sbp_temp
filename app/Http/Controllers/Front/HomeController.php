<?php

namespace Shopbox\Http\Controllers\Front;

use Illuminate\Http\Request;
use Shopbox\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function index()
    {
        return view('welcome');
    }
}
