<?php

namespace Shopbox\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Shopbox\Http\Controllers\Controller;

class LoginController1 extends Controller
{
    public function showLoginForm()
    {
        return view('themes.stores.'.session('store')->domain.'.'.session('store')->template->theme->slug.'.pages.account');
    }

    public function showLinkRequestForm()
    {
    	return view('themes.stores.'.session('store')->domain.'.'.session('store')->template->theme->slug.'.pages.reset-password');
    }
}
