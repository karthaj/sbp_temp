<?php

namespace Modules\CashOnDelivery\Http\Controllers\DataTable;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Shopbox\Http\Controllers\DataTable\DataTableController;
use Modules\CashOnDelivery\Entities\COD;

class PaymentController extends DataTableController
{

    public function builder() {
        return COD::query();
    }

    protected function getRecords(Request $request)
    { 
        $this->allowDeletion = auth()->user()->can('delete cod');
        $this->allowUpdate = auth()->user()->can('edit cod');
        $this->allowCreate = auth()->user()->can('add cod');
        $builder = $this->builder;
       
        if ($this->hasSearchQuery($request)) {
            $builder = $this->buildSearch($builder, $request);
        }
        try {
            $records = $this->builder->select('cash_on_deliveries.id', 'shipping_zones.zone_name', 'surcharge', 'remark', 'cash_on_deliveries.status')->where('cash_on_deliveries.store_id', $request->tenant()->id)->join('shipping_zones', 'shipping_zones.id', '=', 'cash_on_deliveries.zone_id', 'inner')->orderBy('shipping_zones.zone_name', 'asc')->limit($request->limit)->paginate($request->limit);

            $this->paginator = $records;

            return $records->getCollection();

        } catch (QueryException $e) {
            return [];
        }
    }

    public function update ($id, Request $request)
    {  
        $this->builder->find($id)->update(['status' => $request->status]);
        $this->builder = $this->builder();
    }

    public function destroy ($ids)
    {  
        if(!auth()->user()->can('delete cod')) {
            return;
        }

        $this->builder->whereIn('id', explode(',', $ids))->delete();

    }
    
}
