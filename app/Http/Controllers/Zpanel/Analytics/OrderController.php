<?php

namespace Shopbox\Http\Controllers\Zpanel\Analytics;

use Carbon\Carbon;
use Shopbox\Traits\Metric;
use Illuminate\Http\Request;
use Shopbox\Http\Controllers\Controller;

class OrderController extends Controller
{
	use Metric;

	public function __construct()
	{
		$this->middleware('analytics');
	}

	public function index(Request $request) 
	{
		$range = explode(',', $request->for);
		$range_from = Carbon::parse($range[0]);
		$range_to = Carbon::parse($range[1]);

		$orders = $request->tenant()->orders()->filter($request)->groupBy('date')->get();
		$metrics = $this->metrics($orders, $range[0], $range[1], $request->by, 'orders');

		return response()->json([
	        'metrics' => $metrics,
	        'meta' => [
	            'orders' => $request->tenant()->orders()->whereBetween('created_at', [$range_from->toDateString(), $range_to->toDateString()])->count()
	        ]
	    ]);
	}

	public function sales(Request $request) 
	{
		$range = explode(',', $request->for);
		$range_from = Carbon::parse($range[0]);
		$range_to = Carbon::parse($range[1]);

		$orders = $request->tenant()->orders()->sale($request)->get();
		$metrics = $this->metrics($orders, $range[0], $range[1], $request->by, 'sales');

		return response()->json([
	        'metrics' => $metrics,
	        'meta' => [
	            'sales' => $request->tenant()->setting->currency->iso_code.' '.number_format($orders->sum('metrics'),2)
	        ]
	    ]);
	}
    
}
