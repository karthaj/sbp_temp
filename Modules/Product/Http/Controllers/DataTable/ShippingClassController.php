<?php

namespace Modules\Product\Http\Controllers\DataTable;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Shopbox\Http\Controllers\DataTable\DataTableController;
use Modules\Product\Entities\ShippingClass;

class ShippingClassController extends DataTableController
{

    public function builder() {
        return ShippingClass::query();
    }

    protected function getRecords(Request $request)
    { 
        $this->allowDeletion = auth()->user()->can('delete shipping classes');
        $this->allowUpdate = auth()->user()->can('edit shipping classes');
        $this->allowCreate = auth()->user()->can('add shipping classes');

        $builder = $this->builder;
       
        if ($this->hasSearchQuery($request)) {
            $builder = $this->buildSearch($builder, $request);
        }
        try {
            $records =  $this->builder->select('id', 'name', 'slug', 'status')->where('store_id', $request->tenant()->id)->orderBy('id', 'desc')->limit($request->limit)->paginate($request->limit);

            $this->paginator = $records;

            return $records->getCollection();

        } catch (QueryException $e) {
            return [];
        }
    }

    public function update ($id, Request $request)
    {  
        $this->builder->find($id)->update([ 'status' => $request->status]);
        $this->builder = $this->builder();
    }

    public function destroy ($ids)
    {  
        if(!auth()->user()->can('delete shipping classes')) {
            return;
        }

        $this->builder->whereIn('id', explode(',', $ids))->delete();

    }
    
}
