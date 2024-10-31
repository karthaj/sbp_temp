<?php

namespace Modules\Page\Http\Controllers\DataTable;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Shopbox\Http\Controllers\DataTable\DataTableController;
use Modules\Page\Entities\Page;

class PageController extends DataTableController
{
    public function builder() {
        return Page::query();
    }

    protected function getRecords(Request $request)
    { 
        $this->allowDeletion = auth()->user()->can('delete pages');
        $this->allowUpdate = auth()->user()->can('edit pages');
        $this->allowCreate = auth()->user()->can('add pages');

        $builder = $this->builder;
       
        if ($this->hasSearchQuery($request)) {
            $builder = $this->buildSearch($builder, $request);
        }
        try {
            $records =  $this->builder->select('id', 'title', 'slug', 'active')
                        ->where('title', 'like', '%'.$request->q.'%')
                        ->where('store_id', $request->tenant()->id)->orderBy('id', 'desc')->limit($request->limit)->paginate($request->limit);
            
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
        if(!auth()->user()->can('delete pages')) {
            return;
        }

        $this->builder->whereIn('id', explode(',', $ids))->delete();

    }

}
