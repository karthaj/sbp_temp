<?php

namespace Shopbox\Http\Controllers\Zpanel;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Shopbox\Models\Zpanel\Transaction;
use Shopbox\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;

class DashboardController extends Controller
{
    public function index (Request $request) 
    {
 		$payouts = 0;
    	$orders = $request->tenant()->orders()->with(['state' => function ($query) {
                        $query->where('slug', '<>', 'payment-error')
                            ->where('slug', '<>', 'incomplete');
                    }])->whereDate('created_at', Carbon::now(session('store')->setting->timezone->timezone)->toDateString())->count();

    	$sales = $request->tenant()->orders()->whereDate('created_at_tz', Carbon::now(session('store')->setting->timezone->timezone)->toDateString())->sum('total_real_paid');

    	$visits = $request->tenant()->visits()->whereDate('created_at', Carbon::now(session('store')->setting->timezone->timezone)->toDateString())->count();

    	$awaiting_shipment = $request->tenant()->orders()->whereHas('state', function ($query) {
                            $query->where('slug', 'ready-for-shipment');
                        })->count();

		$shipped = $request->tenant()->orders()->whereHas('state', function ($query) {
                            $query->where('slug', 'shipped');
                        })->count();

		$pending_orders = $request->tenant()->orders()->whereHas('state', function ($query) {
    			    		$query->where('slug', 'processing');
    			    	})->count();

		$new_orders = $request->tenant()->orders()->whereHas('state', function ($query) {
                            $query->where('slug', 'pending');
                        })->count();

		$pending_payments = $request->tenant()->orders()->where('total_real_paid', '>', 0)->with(['state' => function ($query) {
			    		$query->where('slug', 'delivered');
			    	}])->count();
    	if(Transaction::where('client_id', $request->tenant()->id)->latest()->count()) {
    		$payouts = Transaction::where('client_id', $request->tenant()->id)->latest()->first()->balance;
    	}

        return view('zpanel.dashboard.index', compact('orders','sales','visits','awaiting_shipment','shipped','pending_payments','pending_orders','new_orders', 'payouts'));
    }

    
}
