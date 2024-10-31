<?php

namespace Modules\Product\Http\Controllers\DataTable;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Modules\Product\Entities\Transfer;
use Modules\Product\Transformers\TransferCollectionTransformer;

class StockRequestController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()) {
            return abort('404');
        }

        if($request->store) {
            $transfers = Transfer::where('stock_request', 1)->where('store_location_id', $request->store)->latest()->paginate($request->limit);
        } else {
            $transfers = Transfer::where('stock_request', 1)->latest()->paginate($request->limit); 
        }
        
        
        $transfer_collections = $transfers->getCollection();
        return  fractal()
                ->collection($transfer_collections)
                ->transformWith(new TransferCollectionTransformer)
                ->paginateWith(new IlluminatePaginatorAdapter($transfers))
                ->toArray();
    }
}
