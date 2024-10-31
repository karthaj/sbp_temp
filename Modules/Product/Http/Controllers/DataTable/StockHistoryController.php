<?php

namespace Modules\Product\Http\Controllers\DataTable;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Shopbox\Http\Controllers\DataTable\DataTableController;
use Modules\Product\Entities\StockTransfer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Modules\Product\Transformers\StockTransferCollectionTransformer;

class StockHistoryController extends Controller
{
    /*public function builder() {
        return StockTransfer::query();
    }   

    public function getDisplayableColumns()
    {
        return [
            'stock_id', 'entity', 'user_firstname', 'user_lastname', 'stock_transfer_reason_id', 'sign', 'quantity', 'created_at'
        ];
    }

    protected function getRecords(Request $request)
    { 
        $builder = $this->builder;
        if ($this->hasSearchQuery($request)) {
            $builder = $this->buildSearch($builder, $request);
        }
        try {
            return $this->builder->where('store_id', $request->tenant()->id)->limit($request->limit)->latest()->get($this->getDisplayableColumns())->load('stock:id,product_id', 'stock.product:id,name', 'stock.product.images:product_id,small_default,cover', 'reason:id,name');
        } catch (QueryException $e) {
            return [];
        }
    }

    public function getCustomColumnNames()
    {
        return [
            'stock_transfer_reason_id' => 'reason',
            'user_firstname' => 'employee',
            'created_at_tz' => 'date & time'
        ];
    }*/

    public function index(Request $request)
    {
        if(!$request->ajax()) {
            return abort('404');
        }

        $transfers = StockTransfer::latest()->paginate($request->limit);
        $transfer_collections = $transfers->getCollection();
        return  fractal()
                ->collection($transfer_collections)
                ->transformWith(new StockTransferCollectionTransformer)
                ->paginateWith(new IlluminatePaginatorAdapter($transfers))
                ->toArray();
    }
    

}
