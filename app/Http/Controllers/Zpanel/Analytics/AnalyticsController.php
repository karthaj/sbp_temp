<?php

namespace Shopbox\Http\Controllers\Zpanel\Analytics;

use Illuminate\Http\Request;
use Shopbox\Http\Controllers\Controller;

class AnalyticsController extends Controller
{
    public function index()
    {
    	return view('zpanel.analytics.index');
    }
}
