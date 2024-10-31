<?php

namespace Modules\Product\Http\Controllers\DataTable;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Shopbox\Http\Controllers\DataTable\DataTableController;
use Modules\Product\Entities\Category;
use Modules\Product\Transformers\Datatable\CategoryCollectionTransformer;

class CategoryController extends DataTableController
{ 

    public function builder() {
        return Category::query();
    }

    protected function getRecords(Request $request)
    { 
        $this->allowDeletion = auth()->user()->can('delete categories');
        $this->allowUpdate = auth()->user()->can('edit categories');
        $this->allowCreate = auth()->user()->can('add categories');
        $builder = $this->builder;
       
        if ($this->hasSearchQuery($request)) {
            $builder = $this->buildSearch($builder, $request);
        }
        try {

            $records = $this->builder->select('child.id', 'child.name', 'child.slug', 'child.sort_order', 'child.status')->join('categories as child', 'child.parent_id', '=', 'categories.id')->whereNull('categories.parent_id')->where('categories.store_id', $request->tenant()->id)->orderBy('id', 'desc')->limit($request->limit)->paginate($request->limit);

            $this->paginator = $records;

            $records = fractal()
                        ->collection($records->getCollection())
                        ->transformWith(new CategoryCollectionTransformer)
                        ->toArray();

            return $records['data'];

        } catch (QueryException $e) {
            return [];
        }
    }

     public function update ($id, Request $request)
    {  
        $this->builder->find($id)->update(['status' => $request->status]);
        $this->builder = $this->builder();
    }

    public function destroy ($id)
    {  
        if(!auth()->user()->can('delete categories')) {
            return;
        }

        $category = $this->builder->where('store_id', session('store')->id)->where('id',$id)->first();

        if(!$category) {
            return;
        }

        $this->removeParent($category);
        
        $this->deleteCategoryImage($category);

        $category->delete();
    
    }

    protected function removeParent(Category $category) 
    {
        $parent = Category::where('store_id', session('store')->id)->where('is_root_category', 1)->first();
        if($category->children->count()) {
            foreach($category->children as $category) {
                $category->parent()->associate($parent);
                $category->save();
            }
        }
    }

    protected function deleteCategoryImage(Category $category)
    {
        if(file_exists(public_path('stores').'/'.request()->tenant()->domain.'/category/'.$category->cover_image)) {
            unlinkFile(public_path('stores').'/'.request()->tenant()->domain.'/category/'.$category->cover_image);
        }

        if(file_exists(public_path('stores').'/'.request()->tenant()->domain.'/category/'.$category->thumb_image)) {
            unlinkFile(public_path('stores').'/'.request()->tenant()->domain.'/category/'.$category->thumb_image);
        }
    }

}
