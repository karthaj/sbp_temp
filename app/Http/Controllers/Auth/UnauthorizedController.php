<?php

namespace Shopbox\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Shopbox\Http\Controllers\Controller;

class UnauthorizedController extends Controller
{

	public function __construct()
	{
		$this->middleware('auth:admin');
	}

    public function unauthorize()
    {
        return abort('400');
    }
}
