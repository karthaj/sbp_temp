<?php

namespace Modules\Product\Http\Controllers\DataTable;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Shopbox\Http\Controllers\DataTable\DataTableController;
use Modules\Product\Entities\Attribute;

class AttributeController extends DataTableController
{
    public function builder() {
        return Attribute::query();
    }

    protected function getRecords(Request $request)
    { 
        $this->allowDeletion = auth()->user()->can('delete variations');
        $this->allowUpdate = auth()->user()->can('edit variations');
        $this->allowCreate = auth()->user()->can('add variations');

        $builder = $this->builder;
       
        if ($this->hasSearchQuery($request)) {
            $builder = $this->buildSearch($builder, $request);
        }
        try {
            $records = $this->builder->select('id', 'name', 'public_name', 'group_type')->where('store_id', $request->tenant()->id)
                        ->where('name', 'like', '%'.$request->q.'%')
                        ->orderBy('id', 'desc')->limit($request->limit)->paginate($request->limit);

            $this->paginator = $records;

            return $records->getCollection();

        } catch (QueryException $e) {
            return [];
        }
    }


    public function destroy ($id)
    {  
        if(!auth()->user()->can('delete variations')) {
            return;
        }
        
        $id = explode(",",$id);
          
        for($i = 0; $i < count($id); $i++) { 
            $this->unlinkPattern($id[$i]);
            $this->builder->where('store_id', request()->tenant()->id)->where('id',$id[$i])->delete();
            $this->builder = $this->builder();
        } 

    }

    protected function unlinkPattern($id)
    {
        $attribute = Attribute::find($id);
        $file = public_path('stores').'/'.request()->tenant()->domain.'/pattern/';
        if($attribute->options->count()) {
            foreach ($attribute->options as $option) {
                if(file_exists($file.$option->pattern)) {
                    unlinkFile($file.$option->pattern);
                }
            }
        }
    }

}
