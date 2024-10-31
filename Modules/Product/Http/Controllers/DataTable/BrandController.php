<?php

namespace Modules\Product\Http\Controllers\DataTable;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Shopbox\Http\Controllers\DataTable\DataTableController;
use Modules\Product\Entities\Brand;
use Modules\Product\Transformers\Datatable\BrandCollectionTransformer;

class BrandController extends DataTableController
{ 
    protected $allowDeletion = true;

    public function builder() {
        return Brand::query();
    }

    protected function getRecords(Request $request)
    { 
        $this->allowDeletion = auth()->user()->can('delete brands');
        $this->allowUpdate = auth()->user()->can('edit brands');
        $this->allowCreate = auth()->user()->can('add brands');
        
        $builder = $this->builder;
       
        if ($this->hasSearchQuery($request)) {
            $builder = $this->buildSearch($builder, $request);
        }
        try {
            
            $records = $this->builder->select('id', 'name', 'slug', 'small_default')->where('store_id', $request->tenant()->id)
                        ->with(['products', 'store'])
                        ->where('name', 'like', '%'.$request->q.'%')
                        ->orderBy('id', 'desc')->limit($request->limit)->paginate($request->limit);

            $this->paginator = $records;

            $records = fractal()
                        ->collection($records->getCollection())
                        ->transformWith(new BrandCollectionTransformer)
                        ->toArray();

            return $records['data'];

        } catch (QueryException $e) {
            return [];
        }
    }

    public function destroy ($ids)
    {  
        if(!auth()->user()->can('delete brands')) {
            return;
        }

        $brand = $this->builder->where('store_id', auth()->user()->stores()->first()->id)->whereIn('id', explode(',', $ids))->first();
        $brand->delete();
        $this->unlinkFile($brand);


    }

    protected function unlinkFile(Brand $brand)
    {
        $file = config('module_product_file.store.absolute').'/'.auth()->user()->stores()->first()->store_name.'/brand';
       
        if(file_exists($file.$brand->small_default) && $brand->small_default != null) {
            unlink($file.$brand->small_default);
        }

        if(file_exists($file.$brand->medium_default) && $brand->medium_default != null) {
            unlink($file.$brand->medium_default);
        }

        if(file_exists($file.$brand->large_default) && $brand->large_default != null) {
            unlink($file.$brand->large_default);
        }
        
    }
}
