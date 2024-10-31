<?php

namespace Modules\Product\Http\Controllers\DataTable;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Modules\Product\Entities\Transfer;
use Modules\Product\Transformers\TransferCollectionTransformer;

class StockReturnController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax()) {
            return abort('404');
        }
        
        $transfers = Transfer::where('stock_request', 2)->latest()->paginate($request->limit);
        
        $transfer_collections = $transfers->getCollection();
        return  fractal()
                ->collection($transfer_collections)
                ->transformWith(new TransferCollectionTransformer)
                ->paginateWith(new IlluminatePaginatorAdapter($transfers))
                ->toArray();
    }
}
