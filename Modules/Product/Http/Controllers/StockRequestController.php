<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Jenssegers\Agent\Agent;
use Modules\Product\Entities\Transfer;
use Modules\Product\Traits\StockTrait;
use Modules\Product\Http\Requests\Stock\StockTransferFormRequest;

class StockRequestController extends Controller
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
        return view('product::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request)
    {
        $stores = $request->tenant()->storeLocations()->where('active', 1)->get();
        return view('product::stocks.request', compact('stores'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(StockTransferFormRequest $request)
    {
        $transfer = new Transfer;
        $transfer->store()->associate($request->tenant());
        $transfer->reference = str_random(10);
        $transfer->type = $request->transfer_type;
        $transfer->stock_request = 1;
        $transfer->remarks = $request->remarks;
        $store = $request->tenant()->storeLocations()->where('id', $request->transfer_store)->first();
        if(!empty($store)) {
            $transfer->store_location()->associate($store);
        } 

        $transfer->save();

        foreach ($request->stocks as $stock) {
            $this->saveStockTransfer($transfer, $request->transfer_store, $stock);
        }
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('product::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit()
    {
        return view('product::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request)
    {
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy()
    {
    }
}
