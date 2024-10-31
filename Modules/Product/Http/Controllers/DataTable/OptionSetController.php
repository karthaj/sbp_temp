<?php

namespace Modules\Product\Http\Controllers\DataTable;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Shopbox\Http\Controllers\DataTable\DataTableController;
use Modules\Product\Entities\OptionSet;

class OptionSetController extends DataTableController
{ 
    public function builder() {
        return OptionSet::query();
    }

    protected function getRecords(Request $request)
    { 
        $this->allowDeletion = auth()->user()->can('delete variant sets');
        $this->allowUpdate = auth()->user()->can('edit variant sets');
        $this->allowCreate = auth()->user()->can('add variant sets');
        $builder = $this->builder;
       
        if ($this->hasSearchQuery($request)) {
            $builder = $this->buildSearch($builder, $request);
        }
        try {
            $records = $this->builder->select('id', 'name')->where('store_id', $request->tenant()->id)
                        ->where('name', 'like', '%'.$request->q.'%')
                        ->orderBy('id', 'desc')->limit($request->limit)->paginate($request->limit);
            
            $this->paginator = $records;

            return $records->getCollection();

        } catch (QueryException $e) {
            return [];
        }
    }

    public function destroy ($ids)
    {  
        if(!auth()->user()->can('delete variant sets')) {
            return;
        }

        $this->builder->whereIn('id', explode(',', $ids))->delete();

    }

}
