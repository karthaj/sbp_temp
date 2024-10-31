<?php

namespace Modules\Product\Http\Controllers\DataTable;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Shopbox\Http\Controllers\DataTable\DataTableController;
use Modules\Product\Entities\StoreLocation;

class StoreController extends DataTableController
{
    protected $allowDeletion = false;

    public function builder() {
        return StoreLocation::query();
    }

    protected function getRecords(Request $request)
    { 
        $this->allowUpdate = auth()->user()->can('edit locations');
        $builder = $this->builder;
       
        if ($this->hasSearchQuery($request)) {
            $builder = $this->buildSearch($builder, $request);
        }
        try {
            $records = $this->builder->select('id', 'name', 'address', 'city', 'phone', 'active', 'online_sales')->where('store_id', $request->tenant()->id)->orderBy('id', 'desc')->limit($request->limit)->paginate($request->limit);

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
}
