<?php

namespace Shopbox\Http\Controllers\Front\Customer;

use Illuminate\Http\Request;
use Shopbox\Http\Controllers\Controller;
use Shopbox\Transformers\StoreFront\CustomerTransformer;

class IndexController extends Controller
{
    public function index()
    {
        return fractal()->item(auth()->user())->transformWith(new CustomerTransformer)->toArray();
    }
}
