<?php

namespace Modules\Customer\Http\Controllers\DataTable;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Shopbox\Http\Controllers\DataTable\DataTableController;
use Modules\Customer\Entities\Group;

class GroupController extends DataTableController
{
    public function builder() {
        return Group::query();
    }

    protected function getRecords(Request $request)
    { 
        $this->allowDeletion = auth()->user()->can('delete customer group');
        $this->allowUpdate = auth()->user()->can('edit customer group');
        $this->allowCreate = auth()->user()->can('add customer group');
        
        $builder = $this->builder;
       
        if ($this->hasSearchQuery($request)) {
            $builder = $this->buildSearch($builder, $request);
        }
        try {
            $records = $this->builder->select('id', 'name', 'discount')->where('store_id', $request->tenant()->id)->orderBy('id', 'desc')->limit($request->limit)->paginate($request->limit);

            $this->paginator = $records;

            return $records->getCollection();
            
        } catch (QueryException $e) {
            return [];
        }
    }

    public function destroy ($ids)
    {  
        if(!auth()->user()->can('delete customer group')) {
            return;
        }

        $this->builder->whereIn('id', explode(',', $ids))->delete();

    }

}
