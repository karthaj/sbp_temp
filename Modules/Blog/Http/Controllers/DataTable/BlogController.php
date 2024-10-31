<?php

namespace Modules\Blog\Http\Controllers\DataTable;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Shopbox\Http\Controllers\DataTable\DataTableController;
use Modules\Blog\Entities\Blog;

class BlogController extends DataTableController
{
    public function builder() {
        return Blog::query();
    }

    protected function getRecords(Request $request)
    { 
        $this->allowDeletion = $request->user()->can('delete blogs');
        $this->allowUpdate = $request->user()->can('edit blogs');
        $this->allowCreate = auth()->user()->can('add blogs');
        $builder = $this->builder;
       
        try {
            $records =  $this->builder->select('id', 'title', 'slug', 'author','created_at')
                        ->where('title', 'like', '%'.$request->q.'%')
                        ->where('store_id', $request->tenant()->id)
                        ->orderBy('id', 'desc')->limit($request->limit)->paginate($request->limit);
            
            $this->paginator = $records;

            return $records->getCollection();
            
        } catch (QueryException $e) {
            return [];
        }
    }

    public function destroy ($ids)
    {  
        if(!auth()->user()->can('delete blogs')) {
            return;
        }

        $this->builder->whereIn('id', explode(',', $ids))->delete();

    }

}
