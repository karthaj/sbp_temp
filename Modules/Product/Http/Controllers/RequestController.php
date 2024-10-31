<?php

namespace Modules\Product\Http\Controllers;

use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Product\Transformers\TransferTransformer;
use Modules\Product\Transformers\TransferStatusTransformer;
use Modules\Product\Entities\Transfer;
use Modules\Product\Traits\StockTrait;

class RequestController extends Controller
{
    use StockTrait;

    protected $agent;

    public function __construct()     
    {
        $this->agent = new Agent();
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        $stores = auth()->user()->storeLocations;
        return view('product::stocks.requests', compact('stores'));
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('product::create');
    }

    public function update(Request $request, Transfer $transfer)
    {

        if((int) $request->status === 1) {

            $this->transferStock($transfer);
            $transfer->status = $request->status;
            $transfer->save();

        } else if((int) $request->status === 2) {

            $this->rollbackStock($transfer);
            $transfer->status = $request->status;
            $transfer->save();

        }

        
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show(Request $request, Transfer $transfer)
    {

        if(!$request->ajax()) {
            return abort('404');
        }

        return  fractal()
                ->item($transfer)
                ->transformWith(new TransferTransformer)
                ->toArray();
    }

    public function viewStatus(Request $request, Transfer $transfer)
    {
        if(!$request->ajax()) {
            return abort('404');
        }

        return  fractal()
                ->item($transfer)
                ->transformWith(new TransferStatusTransformer)
                ->toArray();
    }
}
