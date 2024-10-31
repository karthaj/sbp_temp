<?php

namespace Shopbox\Http\Controllers\Zpanel;

use Illuminate\Http\Request;
use Shopbox\Models\Zpanel\Billing;
use Shopbox\Http\Controllers\Controller;

class BillingController extends Controller
{
    public function index(Billing $billing)
    {
    	return view('zpanel.bills.index', compact('billing'));
    }
}
