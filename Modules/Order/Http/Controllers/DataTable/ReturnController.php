<?php

namespace Modules\Order\Http\Controllers\DataTable;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Modules\Order\Transformers\ReturnCollectionTransformer;
use Modules\Order\Entities\OrderReturn;
use Shopbox\Http\Controllers\DataTable\DataTableController;

class ReturnController extends DataTableController
{
    public function builder() {
        return OrderReturn::query();
    }

    protected function getRecords(Request $request)
    { 
        $this->allowDeletion = $request->user()->can('delete returns');
        $this->allowUpdate = $request->user()->can('edit returns');
        $this->allowCreate = auth()->user()->can('delete returns');
        $builder = $this->builder;
       
        if ($this->hasSearchQuery($request)) {
            $builder = $this->buildSearch($builder, $request);
        }
        try {
            $records = $this->builder->where('store_id', $request->tenant()->id)->orderBy('id', 'desc')->limit($request->limit)->paginate($request->limit);
            
            $this->paginator = $records;

            $records = fractal()
                ->collection($records->getCollection())
                ->transformWith(new ReturnCollectionTransformer($request->channel))
                ->toArray();

            return $records['data'];

        } catch (QueryException $e) {
            return [];
        }
    }

}
