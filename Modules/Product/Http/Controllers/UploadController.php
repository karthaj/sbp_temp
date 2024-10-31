<?php

namespace Modules\Product\Http\Controllers;

use Storage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Shopbox\Http\Controllers\Controller;
use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;
use Modules\Product\Entities\Helper;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductImage;
use Shopbox\Models\Zpanel\ImageDimension;
use Shopbox\Models\Zpanel\Store;

class UploadController extends Controller
{ 
    protected $imageManager;

    public function __construct(ImageManager $imageManager)     
    { 

        $this->imageManager = $imageManager;
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Product $product, Request $request)
    { 
        $this->authorize('touch', $product);
        $uploadedFile = $request->file('file');
        $product_image = $this->storeUpload($uploadedFile, $product, $request->tenant());
        
        return response()->json([
            'id' =>  $product_image->id,
            'sort_order' => $product_image->sort_order,
            'cover' => $product_image->cover,
            'alt_text' => $product_image->alt_text,
            'url_update' => url('/merchant/upload/update/'.$product->slug.'/'.$product_image->id),
            'url_delete' => url('merchant/upload/'.$product->slug.'/'.$product_image->id),
            'error' => 0
        ]);
    }
    
    protected function storeUpload(UploadedFile $uploadedFile, Product $product, Store $store)
    { 
        $product_image = new ProductImage;

        if (!ProductImage::getCover($product->id)) {
            $product_image->cover = 1;
        } else {
            $product_image->cover = 0;
        }

        $product_image->sort_order = ProductImage::getHighestPosition($product->id) + 1;
        $product_image->product()->associate($product);
        $product_image->store()->associate(session('store'));
        $product_image->save();
        $this->uploadProductImage($uploadedFile, $product_image);
        return $product_image;
    } 
    
    public function uploadProductImage(UploadedFile $uploadedFile, ProductImage $product_image)
    {
        $product_image->timestamps = false;
        $path = public_path('stores').'/'.session('store')->domain.'/product';
        
        $dimensions = ImageDimension::where('products', 1)->get();
        $name = str_slug(pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME), '_');
        $extension = $uploadedFile->getClientOriginalExtension();

        foreach($dimensions as $dimension) {
            $processedImage = $this->imageManager->make($uploadedFile->getPathName())
                                 ->resize($dimension->width, $dimension->height, function ($constraint) {
                                    $constraint->aspectRatio();
                                 })
                                ->save($path.'/'.$image_path = $name.'_'.$dimension->width.'x'.$dimension->height.'_'.str_random(10).'.'.$extension);

            if($dimension->name == 'cart_default') {
                $product_image->cart_default = $image_path;
                $product_image->save();
            } else if($dimension->name == 'home_default') {
                $product_image->home_default = $image_path;
                $product_image->save();
            }
            else if($dimension->name == 'small_default') {
                $product_image->small_default = $image_path;
                $product_image->save();
            }
            else if($dimension->name == 'medium_default') {
                $product_image->medium_default = $image_path;
                $product_image->save();
            }
             else if($dimension->name == 'large_default') {
                $product_image->large_default = $image_path;
                $product_image->save();
            }
        } 
    }

    /**
     * Show the form for editing the specified resource.
     * @return Response
     */
    public function edit(Product $product, ProductImage $product_image)
    {
        return response()->json([
            'data' => $this->buildForm($product_image),
        ]);
    }

    protected function buildForm (ProductImage $product_image) 
    {
        $element = '<div class="row">
                        <div class="col-4">
                            <img src="'.asset('stores').'/'.session('store')->domain.'/product/'.$product_image->home_default.'" alt="'.$product_image->alt_text.'" class="img-fluid">
                        </div>
                        <div class="col-8">
                            <div class="form-group">
                              <label for="alt_text">Image alt text</label>
                              <input type="text" name="alt_text" id="alt_text" class="form-control" autocomplete="off" value="'.$product_image->alt_text.'">
                            </div>
                            <div class="checkbox check-info">';
                            if($product_image->cover == 1) {
                                $element .= '<input type="checkbox" value="1" id="form_image_cover" name="cover" checked>';
                            } else {
                                $element .= '<input type="checkbox" value="1" id="form_image_cover" name="cover">';
                            }
            $element .=         '<label for="form_image_cover">Cover</label>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                      <div class="col-12">
                          <div class="no-margin pull-right actions">
                            <button class="btn btn-default mr-2" type="button" onclick="formImagesProduct.delete('.$product_image->id.')">Delete</button>
                            <button class="btn btn-custom-v1" type="button" onclick="formImagesProduct.send('.$product_image->id.')">Save</button>
                          </div>
                      </div>
                    </div>';

        return $element;
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(Request $request, Product $product, ProductImage $product_image)
    {
        if($request->cover != null) {
            if(ProductImage::deleteCover($product)) {
                 $product_image->cover = 1;
            }
        } 
        $product_image->alt_text = $request->alt_text;
        $product_image->updated_at_tz = $product_image->freshTimestamp()->timezone($request->tenant()->setting->timezone->timezone);
        $product_image->save();
    }

    /**
     * Remove the specified resource from storage.
     * @return Response
     */
    public function destroy(Product $product, ProductImage $product_image)
    { 
        $this->authorize('touch', $product);
        $product_image->delete();

        unlinkFile(public_path('stores').'/'.session('store')->domain.'/product/'.$product_image->cart_default);
        unlinkFile(public_path('stores').'/'.session('store')->domain.'/product/'.$product_image->home_default);
        unlinkFile(public_path('stores').'/'.session('store')->domain.'/product/'.$product_image->small_default);
        unlinkFile(public_path('stores').'/'.session('store')->domain.'/product/'.$product_image->medium_default);
        unlinkFile(public_path('stores').'/'.session('store')->domain.'/product/'.$product_image->large_default);

        // if deleted image was the cover, change it to the first one
        if (!ProductImage::getCover($product->id)) {  
            $product_image = ProductImage::where('product_id',$product->id)->first();
            if($product_image != null) {
                $product_image->cover = 1;
                $product_image->save();
            }
        }
        // rearrage the sort order
        $images = ProductImage::where('product_id',$product->id)->get();
        for ($i=0; $i < count($images); $i++) { 
            $images[$i]->sort_order = $i + 1;
        }
    } 

    public function sort(Request $request)
    { 
        $images = json_decode($request->json, true);
        foreach ($images as $id => $position) { 
            $product_image = ProductImage::find($id);
            $product_image->sort_order = (int)$position;
            $product_image->save();
        }
    } 

    public function bulkUpload(Request $request)
    {
        $uploadedFile = $request->file('file');
        $path = public_path('stores').'/'.session('store')->domain.'/product';
        $dimensions = ImageDimension::where('products', 1)->get();
        $name = str_slug(pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME),'_');
        $extension = $uploadedFile->getClientOriginalExtension();
        $product_image = $request->tenant()->productImages()->where('cart_default', $uploadedFile->getClientOriginalName())->first();

        if($product_image) {
            foreach($dimensions as $dimension) {
                $processedImage = $this->imageManager->make($uploadedFile->getPathName())
                                     ->resize($dimension->width, $dimension->height, function ($constraint) {
                                        $constraint->aspectRatio();
                                     })
                                    ->save($path.'/'.$image_path = $name.'_'.$dimension->width.'x'.$dimension->height.'_'.str_random(10).'.'.$extension);

                if($dimension->name == 'cart_default') {
                    $product_image->cart_default = $image_path;
                    $product_image->save();
                } else if($dimension->name == 'home_default') {
                    $product_image->home_default = $image_path;
                    $product_image->save();
                }
                else if($dimension->name == 'small_default') {
                    $product_image->small_default = $image_path;
                    $product_image->save();
                }
                else if($dimension->name == 'medium_default') {
                    $product_image->medium_default = $image_path;
                    $product_image->save();
                }
                 else if($dimension->name == 'large_default') {
                    $product_image->large_default = $image_path;
                    $product_image->save();
                }
            } 

            return response()->json([
                'data' => 'Image(s) uploaded successfully.'
            ]);
        }

    }

}
