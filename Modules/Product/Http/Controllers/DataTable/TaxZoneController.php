<?php

namespace Modules\Product\Http\Controllers\DataTable;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Shopbox\Http\Controllers\DataTable\DataTableController;
use Modules\Product\Entities\TaxZone;


class TaxZoneController extends DataTableController
{
    
    public function builder() {
        return TaxZone::query();
    }

    protected function getRecords(Request $request)
    { 
        $this->allowDeletion = auth()->user()->can('delete tax zones');
        $this->allowUpdate = auth()->user()->can('edit tax zones');
        $builder = $this->builder;
       
        if ($this->hasSearchQuery($request)) {
            $builder = $this->buildSearch($builder, $request);
        }
        try {
            $records =  $this->builder->select('id', 'name', 'type', 'status')->where('store_id', $request->tenant()->id)->orderBy('id', 'desc')->limit($request->limit)->paginate($request->limit);

            $this->paginator = $records;

            return $records->getCollection();
            
        } catch (QueryException $e) {
            return [];
        }
    }

    public function update ($id, Request $request)
    {  
        $this->builder->find($id)->update($request->only('status'));
        $this->builder = $this->builder();
    }

    public function destroy ($ids)
    {  
        if(!auth()->user()->can('delete tax zones')) {
            return;
        }

        $this->builder->whereIn('id', explode(',', $ids))->delete();

    }
}
