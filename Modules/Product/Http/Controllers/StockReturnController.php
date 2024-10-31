<?php

namespace Modules\Product\Http\Controllers;

use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Product\Entities\Transfer;
use Modules\Product\Traits\StockTrait;
use Modules\Product\Http\Requests\Stock\StockTransferFormRequest;
use Modules\Product\Transformers\StockTransformer;
use Illuminate\Routing\Controller;

class StockReturnController extends Controller
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
        return view('product::stocks.returns');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Request $request)
    {
        $stores = $request->tenant()->storeLocations()->where('active', 1)->get();
        return view('product::stocks.return', compact('stores'));
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
        $transfer->stock_request = 2;
        $transfer->remarks = $request->remarks;
        $store = $request->tenant()->storeLocations()->where('id', $request->transfer_store)->first();
        if(!empty($store)) {
            $transfer->store_location()->associate($store);
        } 

        $transfer->save();

        foreach ($request->stocks as $stock) {
            $this->saveStockReturn($transfer, $store, $stock);
        }
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function getProduct(Request $request)
    {
    
        $stock = $request->tenant()->stocks()->where('id', $request->stock_id)->first();

        $store = $request->tenant()->storeLocations()->where('id', $request->store)->first();

        $stock_transformer = fractal()->item($stock)->transformWith(new StockTransformer($store))->toArray();

        $stock = $stock_transformer['data'];

        return response()->json(compact('stock'));
    }

}
