<?php

namespace Modules\Product\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Product\Http\Requests\Category\CategoryFormRequest;
use Intervention\Image\ImageManager;
use Shopbox\Models\Zpanel\Store;
use Shopbox\Models\Zpanel\ImageDimension;
use Modules\Product\Entities\Category;
use Modules\Product\Entities\Helper;
use Illuminate\Http\UploadedFile;


class CategoryController extends Controller
{ 
    protected $imageManager;

    public function __construct(ImageManager $imageManager)     
    {
        $this->imageManager = $imageManager;
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {  
        return view('product::categories.index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {   
        $categories = Category::where('status', 1)->get();
        $category = Category::where('is_root_category', 1)->first()->id;
        return view('product::categories.create', compact('categories', 'category'));
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(CategoryFormRequest $request)
    {  
        $category = new Category;
        $category->parent_id = $request->category;
        $category->name = $request->name;
        $category->description = htmlentities($request->description);
        $category->slug = str_slug($request->url_handle, '-');
        $category->meta_title = $request->page_title;
        $category->meta_description = $request->meta_description;
        $category->meta_keywords = $request->meta_keywords;
        $category->sort_order = $request->sort_order;
        $category->status = $request->status ? $request->status : 0;
        $category->save();
           
        if($request->cover_image != null) { 

            $this->uploadCategoryImage($request->file('cover_image'), $category);
                
        }

        

        return redirect()->route('categories.index')->withSuccess('Category created successfully!');
    }

    /**
     * Show the specified resource.
     * @return Response
     */
    public function show()
    {
        return view('product::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(Request $request, Category $category)
    { 
        $categories = Category::where('status', 1)->get(); 
        $products = $request->tenant()->products;
        return view('product::categories.edit', compact('category', 'categories', 'products'));
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(CategoryFormRequest $request, Category $category)
    {  
        $category->parent_id = $request->category;
        $category->name = $request->name;
        $category->description = htmlentities($request->description);
        $category->slug = str_slug($request->url_handle, '-');
        $category->meta_title = $request->page_title;
        $category->meta_description = $request->meta_description;
        $category->meta_keywords = $request->meta_keywords;
        $category->sort_order = $request->sort_order;
        $category->status = $request->status ? $request->status : 0;
        $category->save();

        if($request->cover_image != null) { 

            $this->uploadCategoryImage($request->file('cover_image'), $category);               
        }
        
        return redirect()->route('categories.index')->withSuccess('Category updated successfully!');
    }

    protected function uploadCategoryImage(UploadedFile $uploadedFile, Category $category)
    {
        $dimensions = ImageDimension::where('categories', 1)->get();
        $path = public_path('stores').'/'.session('store')->domain.'/category';
        $name = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $uploadedFile->getClientOriginalExtension();
        
        $category->timestamps = false;

        foreach($dimensions as $dimension) {
            $this->imageManager->make($uploadedFile->getPathName())
                 ->resize($dimension->width, $dimension->height, function ($constraint) {
                    $constraint->aspectRatio();
                 })
                 ->save($path.'/'.$image_path = $name.'_'.$dimension->width.'x'.$dimension->height.'_'.str_random(10).'.'.$extension);
            if($dimension->name == 'category_default') {
                $category->cover_image = $image_path;
            } else {
                $category->thumb_image = $image_path;
            }
            
        }

        $category->save();
    }

    public function addProduct(Request $request, Category $category)
    {
        $product = $request->tenant()->products()->where('id', $request->product_id)->first();

        if(!$product) {
            return;
        }

        if($category->products->contains('id', $product->id)) {
            return;
        }

        $category->products()->syncWithoutDetaching($product->id);

        $list = '<li class="list-group-item d-flex align-items-center justify-content-between">
                        <div class="p-2">';
                        if($product->images()->where('cover', 1)->count()): 
        $list.=          '<img src="'.asset('stores').'/'.$request->tenant()->domain.'/product/'.$product->images()->where('cover', 1)->first()->small_default.'" alt="'.$product->images()->where('cover', 1)->first()->alt_text.'" class="p-2 img-fluid" width="40">';
                        else:
        $list.=          '<img src="'.asset('assets/img/ProductDefault.gif').'" alt="default image" class="p-2 img-fluid" width="40">';
                        endif;
        $list.=         '<span class="p-2">'.$product->name.'</span>
                        </div>
                        <div class="p-2">
                          <a href="#" data-id="'.$product->id.'" class="remove-product"><i class="aapl-cross"></i></a>
                        </div>
                    </li>';

        return $list;

    }

    public function removeProduct(Request $request, Category $category)
    {
        $product = $request->tenant()->products()->where('id', $request->product_id)->first();

        if(!$product) {
            return;
        }

        $category->products()->detach($product->id);

    }


}
