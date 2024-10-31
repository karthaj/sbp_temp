<?php

namespace Shopbox\Http\Controllers\Zpanel\Analytics;

use Carbon\Carbon;
use Shopbox\Traits\Metric;
use Illuminate\Http\Request;
use Shopbox\Http\Controllers\Controller;

class VisitController extends Controller
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

    	$visits = $request->tenant()->visits()->filter($request)->groupBy('date')->get();
    	$metrics = $this->metrics($visits, $range[0], $range[1], $request->by, 'visits');
 
    	return response()->json([
            'metrics' => $metrics,
            'meta' => [
                'visits' => $request->tenant()->visits()->whereBetween('created_at', [$range_from->toDateString(), $range_to->toDateString()])->count(),
            ]
        ]);

    }
}
