<?php

namespace Modules\Discount\Http\Controllers\DataTable;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Shopbox\Http\Controllers\DataTable\DataTableController;
use Modules\Discount\Entities\Discount;

class DiscountController extends DataTableController
{
    public function builder() {
        return Discount::query();
    }

    protected function getRecords(Request $request)
    { 
        $this->allowDeletion = $request->user()->can('delete discounts');
        $this->allowUpdate = $request->user()->can('edit discounts');
        $this->allowCreate = auth()->user()->can('add discounts');
        $builder = $this->builder;
       
        if ($this->hasSearchQuery($request)) {
            $builder = $this->buildSearch($builder, $request);
        }
        
        try {
            if(!empty($request->q)) { 
                $records = $this->builder->select('id', 'name', 'code', 'active', 'expires_at')
                        ->where('name', 'like', '%'.$request->q.'%')
                        ->where('store_id', 11000)
                        ->orderBy('id', 'desc')->limit($request->limit)->paginate($request->limit);
            } else {
                $records = $this->builder->select('id', 'name', 'code', 'active', 'expires_at')
                        ->where('store_id', $request->tenant()->id)
                        ->orderBy('id', 'desc')->limit($request->limit)->paginate($request->limit);
            }
            
            
            $this->paginator = $records;

            return $records->getCollection();

        } catch (QueryException $e) {
            return [];
        }
    }

    public function update ($id, Request $request)
    {  
        $this->builder->find($id)->update(['active' => $request->status]);
        $this->builder = $this->builder();
    }

    public function destroy ($ids)
    {  
        if(!auth()->user()->can('delete discounts')) {
            return;
        }

        $this->builder->whereIn('id', explode(',', $ids))->delete();

    }
    
}
