<?php

namespace Shopbox\Http\Controllers\Zpanel\Account;

use Illuminate\Http\Request;
use Shopbox\Http\Controllers\Controller;
use Shopbox\Models\Zpanel\User;

class UserController extends Controller
{

    public function index()
    {
        return view('zpanel.account.index');
    }
}
