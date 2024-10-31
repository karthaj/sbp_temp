<?php

namespace Modules\Product\Http\Controllers\DataTable;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Shopbox\Http\Controllers\DataTable\DataTableController;
use Modules\Product\Entities\ShippingZone;

class ShippingZoneController extends DataTableController
{
    
    public function builder() {
        return ShippingZone::query();
    }

    protected function getRecords(Request $request)
    { 
        $this->allowDeletion = auth()->user()->can('delete shipping zones');
        $this->allowUpdate = auth()->user()->can('edit shipping zones');
        $this->allowCreate = auth()->user()->can('configure shipping zones');

        $builder = $this->builder;
       
        if ($this->hasSearchQuery($request)) {
            $builder = $this->buildSearch($builder, $request);
        }
        try {
            $records = $this->builder->select('shipping_zones.id', 'zone_name', 'zone_type', 'display_name', 'shipping_zones.status', 'alias')->where('store_id', $request->tenant()->id)->join('shipping_zone_methods', 'shipping_zone_methods.shipping_zone_id', '=', 'shipping_zones.id', 'inner')->where('shipping_zone_methods.status', 1)->orderBy('shipping_zones.id', 'desc')->limit($request->limit)->paginate($request->limit);

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
        if(!auth()->user()->can('delete shipping zones')) {
            return;
        }

        $this->builder->whereIn('id', explode(',', $ids))->delete();

    }
}
