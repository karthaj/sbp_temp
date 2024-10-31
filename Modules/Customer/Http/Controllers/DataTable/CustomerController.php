<?php

namespace Modules\Customer\Http\Controllers\DataTable;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Shopbox\Http\Controllers\DataTable\DataTableController;
use Modules\Customer\Entities\Customer;

class CustomerController extends DataTableController
{
    protected $allowDeletion = false;
    protected $allowUpdate = false;


    public function builder() {
        return Customer::query();
    }

    protected function getRecords(Request $request)
    { 
        $this->allowDeletion = auth()->user()->can('delete customers');
        $this->allowUpdate = auth()->user()->can('edit customers');
        $this->allowCreate = auth()->user()->can('add customers');

        $builder = $this->builder;
       
        if ($this->hasSearchQuery($request)) {
            $builder = $this->buildSearch($builder, $request);
        }
        try {
            $records = $this->builder->selectRaw('id, firstname, lastname, email, COALESCE(phone, "n/a") as phone, active')->join('store_customers', 'store_customers.customer_id', '=', 'customers.id', 'inner')->where('store_id', $request->tenant()->id)->orderBy('id', 'desc')->limit($request->limit)->paginate($request->limit);

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
        if(!auth()->user()->can('delete customers')) {
            return;
        }

        session('store')->customers()->detach(explode(',', $ids));

    }
}
