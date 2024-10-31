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

class TransferController extends Controller
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
        $stores = session('store')->storeLocations;
        return view('product::stocks.transfers', compact('stores'));
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
        $params['entity'] = $transfer->store_location->id;

        if((int) $request->status === 1) {

            if($transfer->stock_request === 2 && ($transfer->type === 'damage' || $transfer->type === 'stolen')) {

                $this->unsellableStock($transfer);

            } elseif($transfer->stock_request === 2 && $transfer->type === 'return') {

                $params['system_remark'] = 'warehouse <- '.$transfer->store_location->name;
                $this->transferStock($transfer, $params);

            } else {

                $params['system_remark'] = $transfer->store_location->name.' <- warehouse';
                $this->transferStock($transfer, $params, $transfer->store_location);

            }
           
            $transfer->status = $request->status;
            $transfer->save();

        } else if((int) $request->status === 2) {

            if($transfer->stock_request === 2 && ($transfer->type === 'damage' || $transfer->type === 'stolen' || $transfer->type === 'return')) {

                $params['system_remark'] = $transfer->store_location->name.' <- ';
                $this->rollbackStock($transfer, $params, $transfer->store_location);

            }  else {

                $params['system_remark'] = 'warehouse <-';
                $this->rollbackStock($transfer, $params);
            }

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
