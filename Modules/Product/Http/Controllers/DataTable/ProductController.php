<?php

namespace Modules\Product\Http\Controllers\DataTable;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Shopbox\Http\Controllers\DataTable\DataTableController;
use Modules\Product\Entities\Product;
use Modules\Product\Transformers\Datatable\ProductCollectionTransformer;

class ProductController extends DataTableController
{
    
    public function builder() {
        return Product::query();
    }

    protected function getRecords(Request $request)
    { 
        $this->allowDeletion = $request->user()->can('delete products');
        $this->allowUpdate = $request->user()->can('edit products');
        $this->allowCreate = auth()->user()->can('add products');
        
        $builder = $this->builder;
        if ($this->hasSearchQuery($request)) {
            $builder = $this->buildSearch($builder, $request);
        }

        try {
            if(!empty($request->q)) {
                $records = $this->builder->where('store_id', $request->tenant()->id)->where('state', 1)
                            ->where('name', 'like', '%'.$request->q.'%')
                            ->orWhere('store_id', $request->tenant()->id)
                            ->where('sku', 'like', '%'.$request->q.'%')
                            ->orderBy('id', 'desc')->limit($request->limit)->paginate($request->limit);
            } else {
                $records = $this->builder->where('store_id', $request->tenant()->id)->where('state', 1)
                            ->orderBy('id', 'desc')->limit($request->limit)->paginate($request->limit);
            }
            
            $this->paginator = $records;

            $records = fractal()
                        ->collection($records->getCollection())
                        ->transformWith(new ProductCollectionTransformer)
                        ->toArray();

            return $records['data'];

        } catch (QueryException $e) {
            return [];
        }
    }

    public function update ($id, Request $request)
    {  
        $this->builder->find($id)->update([ 'active' => $request->status]);
        $this->builder = $this->builder();
    }

    public function destroy ($ids)
    {  
        if(!auth()->user()->can('delete products')) {
            return;
        }

        $data = explode(',', $ids);

        foreach ($data as $value) {
            $product = Product::find($value);

            if($product) {
                $product->slug = 'deleted';
                $product->save();
            }
        }

        session('store')->stocks()->whereIn('id', explode(',', $ids))->delete();
    
        $this->builder->whereIn('id', explode(',', $ids))->delete();

    }

}
