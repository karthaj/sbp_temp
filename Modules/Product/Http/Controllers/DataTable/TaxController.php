<?php

namespace Modules\Product\Http\Controllers\DataTable;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Shopbox\Http\Controllers\DataTable\DataTableController;
use Modules\Product\Entities\Tax;
use Modules\Product\Transformers\Datatable\TaxRateCollectionTransformer;

class TaxController extends DataTableController
{
    
    public function builder() {
        return Tax::query();
    }

    protected function getRecords(Request $request)
    { 
        $this->allowDeletion = auth()->user()->can('delete tax rates');
        $this->allowUpdate = auth()->user()->can('edit tax rates');
        $this->allowCreate = auth()->user()->can('add tax rates');
        $builder = $this->builder;
        if ($this->hasSearchQuery($request)) {
            $builder = $this->buildSearch($builder, $request);
        }
        try {
            $records = $this->builder->where('store_id', $request->tenant()->id)->limit($request->limit)->paginate($request->limit);

            $this->paginator = $records;

            $records = fractal()
                        ->collection($records->getCollection())
                        ->transformWith(new TaxRateCollectionTransformer)
                        ->toArray();

            return $records['data'];
        } catch (QueryException $e) {
            return [];
        }
    }

    public function destroy ($ids)
    {  
        if(!auth()->user()->can('delete tax rates')) {
            return;
        }

        $this->builder->whereIn('id', explode(',', $ids))->delete();

    }

}
