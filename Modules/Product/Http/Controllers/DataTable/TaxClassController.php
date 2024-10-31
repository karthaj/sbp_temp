<?php

namespace Modules\Product\Http\Controllers\DataTable;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Shopbox\Http\Controllers\DataTable\DataTableController;
use Modules\Product\Entities\TaxClass;

class TaxClassController extends DataTableController
{

    public function builder() {
        
        return TaxClass::query();
    }

    protected function getRecords(Request $request)
    { 
        $this->allowDeletion = auth()->user()->can('delete tax classes');
        $this->allowUpdate = auth()->user()->can('edit tax classes');
        $this->allowCreate = auth()->user()->can('add tax classes');
        $builder = $this->builder;
       
        if ($this->hasSearchQuery($request)) {
            $builder = $this->buildSearch($builder, $request);
        }
        try {
            $records = $this->builder->select('id','name')->where('store_id', $request->tenant()->id)->orderBy('id', 'desc')->limit($request->limit)->paginate($request->limit);

            $this->paginator = $records;

            return $records->getCollection();

        } catch (QueryException $e) {
            return [];
        }
    }

    public function destroy ($ids)
    {  
        if(!auth()->user()->can('delete tax classes')) {
            return;
        }

        $this->builder->whereIn('id', explode(',', $ids))->delete();

    }

}
