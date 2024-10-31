<?php

namespace Shopbox\Http\Controllers\Zpanel\Payout;

use Illuminate\Http\Request;
use Shopbox\Models\Zpanel\Transaction;
use Shopbox\Http\Controllers\Controller;
use Shopbox\Transformers\PayoutTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class PayoutController extends Controller
{
    public function payouts(Request $request)
    {
    	$payouts = Transaction::where('client_id', $request->tenant()->id)->filter($request)->orderBy('id', 'desc')->paginate($request->limit);
    	return  fractal()
                ->collection($payouts->getCollection())
                ->transformWith(new PayoutTransformer)
                ->paginateWith(new IlluminatePaginatorAdapter($payouts))
                ->toArray();
    }
    public function index()
    {
    	return view('zpanel.payouts.index');
    }
}
