<?php

namespace Shopbox\Http\Controllers\Zpanel;

use Illuminate\Http\Request;
use Shopbox\Http\Controllers\Controller;

class ExpireController extends Controller
{
    public function index()
    {
    	return view('zpanel.expire.expired_trial');
    }

    public function expired()
    {
    	return view('zpanel.expire.expired');
    }

    public function suspend()
    {
    	return view('zpanel.expire.suspend');
    }
}
