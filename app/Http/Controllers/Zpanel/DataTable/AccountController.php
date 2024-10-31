<?php

namespace Shopbox\Http\Controllers\Zpanel\DataTable;

use Illuminate\Http\Request;
use Shopbox\Http\Controllers\Controller;
use Shopbox\Http\Controllers\DataTable\DataTableController;
use Shopbox\Models\Zpanel\User;

class AccountController extends DataTableController
{
    public function builder() {
        return User::query();
    }

    protected function getRecords(Request $request)
    { 
    	$this->allowDeletion = auth()->user()->can('delete users');
        $this->allowUpdate = auth()->user()->can('edit users');
    	$this->allowCreate = auth()->user()->can('add users');
        $builder = $this->builder;
       
        if ($this->hasSearchQuery($request)) {
            $builder = $this->buildSearch($builder, $request);
        }
        try {
            $records =  $this->builder->select('id', 'email', 'first_name', 'last_name', 'active')->join('store_users', 'store_users.user_id', '=', 'users.id', 'inner')->where('store_id', $request->tenant()->id)->where('master', 0)->where('id', '<>', auth()->user()->id)->paginate($request->limit);

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
        if(!auth()->user()->can('delete users')) {
            return;
        }

        $this->builder->whereIn('id', explode(',', $ids))->delete();

    }

}
