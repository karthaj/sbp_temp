<?php

namespace Modules\Product\Http\Controllers;

use Storage;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Product\Http\Requests\Product\ProductFormRequest;
use Modules\Product\Http\Requests\Product\ProductUpdateFormRequest;
use Modules\Product\Http\Requests\Product\ProductVariationsFormRequest;
use Modules\Product\Http\Requests\Product\ProductVariationFormRequest;
//use Illuminate\Routing\Controller;
use Shopbox\Http\Controllers\Controller;
use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;
use Jenssegers\Agent\Agent;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\Category;
use Modules\Product\Entities\Brand;
use Modules\Product\Entities\Attribute;
use Modules\Product\Entities\OptionSet;
use Modules\Product\Entities\ProductAttribute;
use Modules\Product\Entities\Combination;
use Modules\Product\Entities\Option;
use Modules\Product\Entities\File;
use Modules\Product\Entities\TaxClass;
use Modules\Product\Entities\Stock;
use Modules\Product\Entities\StoreLocation;
use Modules\Product\Entities\RelatedProduct;
use Modules\Product\Entities\ProductImage;
use Modules\Product\Entities\ShippingClass;
use Shopbox\Models\Zpanel\Store;
use Shopbox\Models\Zpanel\ImageDimension;
use Shopbox\Models\Zpanel\Track;
use Modules\Product\Entities\Helper;
use Modules\Product\Transformers\ProductVariationCollectionTransformer;
use Modules\Product\Traits\StockTrait;


class ProductController extends Controller
{ 
    use StockTrait;

    protected $imageManager;
    protected $agent; 

    public function __construct(ImageManager $imageManager)     
    {
        $this->imageManager = $imageManager;
        $this->agent = new Agent();
    }

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('product::products.index');
    }

    public function bulkEditor()
    {
        return view('product::products.bulk_editor');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create(Product $product)
    {   
        if (!$product->exists) {
            $product = $this->createAndReturnSkeletonProduct();
            return redirect()->route('product.create', $product->slug);
        }
        $this->authorize('touch', $product);
        $store = Store::find(session()->get('tenant'));
        $brands = Brand::all();
        $categories = Category::all();
        $option_sets = OptionSet::all();
        $products = Product::where('state',1)->where('active', 1)->where('id','<>',$product->id)->get();
        $taxes = TaxClass::all();
        $attributes = $this->getProductAttributes($product->variations);
        $shipping_classes = ShippingClass::where('status', 1)->get();

        return view('product::products.create',compact('product','brands','categories','option_sets','products','taxes','store', 'attributes', 'shipping_classes'));
    }
 
    protected function createAndReturnSkeletonProduct()
    { 

        return Product::create(['slug' => uniqid(true)]);
        
    }

    protected function updateStock(Product $product, $qty_threshold)
    {

        if($product->variations && $product->variations->count()) {

            foreach ($product->variations as $attribute) {
                $stock = Stock::where('product_attribute_id', $attribute->id)->first();

                if(!$stock) {
                    $stock = new Stock;
                } 

                $stock->store()->associate($product->store);
                $stock->product()->associate($product);
                $stock->productAttribute()->associate($attribute);
                $stock->out_of_stock = $qty_threshold;
                $stock->save();

                if($product->store->storeLocations->count()) {
                    foreach($product->store->storeLocations  as $location) {
                        $this->incrementStock($stock, 0, $location);
                    }
                }

            }
        }
    }

    protected function saveStock (Product $product, $qty_threshold) 
    {   
        if($product->variations && $product->variations->count()) {

            foreach ($product->variations as $attribute) {
                $this->stockProducts($product, $qty_threshold, $attribute->id);
            }

        } elseif($product->type != 'variant') {

            $this->stockProducts($product, $qty_threshold);

        }
    }

    protected function stockProducts (Product $product, $qty_threshold, $attribute_id = null) 
    {
        $stock = new Stock;
        $stock->store()->associate($product->store);
        $stock->product()->associate($product);

        if($attribute_id) {
            $stock->productAttribute()->associate($attribute_id);
        }

        $stock->out_of_stock = $qty_threshold;
        $stock->save();

        if($product->store->storeLocations->count()) {
            foreach($product->store->storeLocations  as $location) {
                $this->incrementStock($stock, 0, $location);
            }
        }
    }   

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(Product $product, ProductFormRequest $request)
    {   
        $this->authorize('touch', $product);
    
        if(!empty($request->brand)) {

            $product->brand()->associate($request->brand);
            
        } 

        if($request->option_set) {
            $product->optionSet()->associate($request->option_set);
        }

        if($request->shipping_class) {

            $product->shippingClass()->associate($request->shipping_class);
        }

        $product->name = $request->title;
        $product->slug = str_slug($request->url_handle, '-');
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;
        $product->meta_keywords = $request->meta_keywords;
        if(!empty($request->cost_price)) {
            $product->cost_price = $this->getFormattedPrice($request->cost_price);
        }
       
        $product->selling_price = $this->getFormattedPrice($request->selling_price);

        if(!empty($request->special_price)) {
            $special_price = $this->getFormattedPrice($request->special_price);
        }
        
        if(!empty($request->special_start_date)) {
            $product->special_active_on = $request->special_start_date.' '.$this->getFormattedTime($request->special_start_time);   
        }

        if(!empty($request->special_end_date)) {
            $product->special_end_on = $request->special_end_date.' '.$this->getFormattedTime($request->special_end_time);   
        }

        $product->sku = $request->sku;
        $product->barcode = $request->barcode;
        $product->isbn = $request->isbn;
        $product->upc = $request->upc;
        $product->minimal_quantity = $request->min_qty;
        $product->width = (float) str_replace(",","",$request->width);
        $product->height = (float) str_replace(",","",$request->height);
        $product->depth = (float) str_replace(",","",$request->depth);
        $product->weight = (float) str_replace(",","",$request->weight);
        $product->type = $request->product_type;
        $product->tags = $request->tags;
        $product->condition = $request->condition;
        $product->show_condition = $request->show_condition ? 1 : 0;

        if(!empty($request->online)) {
            $product->online = 1;
        }

        if($request->product_availability === 'preorder') {
            $product->pre_order = 1;
            $product->available_for_order = 0;
        } elseif($request->product_availability === 'backorder' || !$request->product_availability) {
            $product->pre_order = 0;
            $product->available_for_order = 1;
            $request->available_date = null;
        } elseif($request->product_availability === 'none') {
            $product->pre_order = 0;
            $product->available_for_order = 0;
            $request->available_date = null;
        }

        if(!empty($request->available_date)) {
            $product->available_date = $request->available_date;
        } elseif(!empty($product->available_date)) {
            $product->available_date = null;
        }

        $product->available_now = $request->instock;
        $product->available_later = $request->outofstock;

        if(!empty($request->publish_date) && !empty($request->publish_time)) {
            $product->publish_on = $request->publish_date.' '.$this->getFormattedTime($request->publish_time);
        }
        
        if($product->publish_on->lessThanOrEqualTo(Carbon::now($request->tenant()->setting->timezone->timezone))) {
             $product->active = 1;
        }

        $product->state = 1;
        $product->categories()->syncWithoutDetaching(explode(',', $request->category));

        if(!empty($request->tax_class)) {

            $product->taxClass()->associate($request->tax_class);
            
        } 
        
        $product->save();

        if(!empty($request->variation)) {
            $variations = $request->variation;
            for ($i=0; $i < count($variations); $i++) { 
                $this->processProductAttribute($product, $variations[$i], $request->tenant());
            }
        } 

        if($request->product_type == 'virtual') {
            if($request->file('product_file') != null) {
                $this->uploadProductFile($request, $product);
            }
        }

        if(!empty($request->related_products)) {
            $product->relatedProducts()->syncWithoutDetaching($request->related_products);
        }
        
        $this->saveStock($product, $request->low_qty);
    
        return redirect()->route('product.list')->withSuccess('Product created successfully!');
    }

    protected function checkProductIsValid(Store $store, $product_id)
    {
        return (bool)$store->products()->where('id', $product_id)->where('state', 1)->where('active', 1)->count();
    }


    /**
     * processProductAttribute
     * Update a combination
     *
     * @param object $product
     * @param array $combinationValues the posted values
     *
     * @return AdminProductsController instance
     */
    public function processProductAttribute($product, $combinationValues, Store $store)
    {
        $product_attribute = ProductAttribute::find((int)$combinationValues['id']);
        if ($product_attribute == null) {
            return;
        }
        $product_attribute->sku = $combinationValues['sku'];
        $product_attribute->selling_price = $combinationValues['selling_price'];
        $product_attribute->created_at_tz = $product_attribute->freshTimestamp()->timezone($store->setting->timezone->timezone);
        $product_attribute->updated_at_tz = $product_attribute->freshTimestamp()->timezone($store->setting->timezone->timezone);
        $product_attribute->save();
        
    }

    protected function uploadProductFile(Request $request, Product $product)
    { 
        $uploadedFile = $request->file('product_file');
        $file = $product->file()->create([
                    'filename' => $uploadedFile->getClientOriginalName(),
                    'size' => $uploadedFile->getSize(),
                    'maximum_downloads' => $request->max_downloads ?: 0,
                    'active' => 1,
                    'created_at_tz' => $product->freshTimestamp()->timezone($request->tenant()->setting->timezone->timezone),
                    'updated_at_tz' => $product->freshTimestamp()->timezone($request->tenant()->setting->timezone->timezone),
                ]);
        Storage::disk('local')->putFileAs(
            'stores/'.$request->tenant()->store_name.'/files'.'/'.$file->id,
            $uploadedFile,
            $uploadedFile->getClientOriginalName()
        );
    }

    protected function deleteProductFile(File $file)
    {
        if($file->product->store_id !== session('store')->id) {
            return;
        }

        Storage::deleteDirectory('stores/'.session('store')->store_name.'/files/'.$file->id);
        $file->delete();
    }

    protected function uploadVariantImage(UploadedFile $uploadedFile, ProductAttribute $product_attribute)
    {
        $product_attribute->timestamps = false;
        $path = public_path('stores').'/'.session('store')->domain.'/product/';
        $dimensions = ImageDimension::where('products', 1)->get();
        $name = str_slug(pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME), '_');
        $extension = $uploadedFile->getClientOriginalExtension();
        $image = new ProductImage;
        $image->product()->associate($product_attribute->product);
        $image->productAttribute()->associate($product_attribute);
        $image->sort_order = $product_attribute->product->images->count() + 1;

        foreach($dimensions as $dimension) {
            $this->imageManager->make($uploadedFile->getPathName())
                 ->resize($dimension->width, $dimension->height, function ($constraint) {
                    $constraint->aspectRatio();
                 })
                 ->save($path.'/'.$image_path = $name.'_'.$dimension->width.'x'.$dimension->height.'_'.str_random(10).'.'.$extension);
            if($dimension->name == 'cart_default') {
                $image->cart_default = $image_path;
                $image->save();
            } else if($dimension->name == 'home_default') {
                $image->home_default = $image_path;
                $image->save();
            }
            else if($dimension->name == 'small_default') {
                $image->small_default = $image_path;
                $image->save();
            }
            else if($dimension->name == 'medium_default') {
                $image->medium_default = $image_path;
                $image->save();
            }
            else if($dimension->name == 'large_default') {
                $image->large_default = $image_path;
                $image->save();
            }
        } 
    }

    protected function getFormattedPrice($price)
    {
        return (float) str_replace(",","",$price);
    }

    protected function getFormattedTime($time)
    {
        $time=date_create($time);
        return date_format($time,'H:i:s');
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
    public function edit(Product $product)
    {
        $store = Store::find(session()->get('tenant'));
        $brands = Brand::all();
        $categories = Category::all();
        $products = Product::where('state',1)->where('active', 1)->where('id','<>',$product->id)->get();
        $publish_on = explode(' ',$product->publish_on);
        $publish_date = $product->publish_on ? $publish_on[0] : '';
        $publish_time = $product->publish_on ? $publish_on[1] : '';
        $option_sets = OptionSet::all();
        $taxes = TaxClass::all();
        $attributes = $this->getProductAttributes($product->variations);
        $shipping_classes = ShippingClass::where('status', 1)->get();

        return view('product::products.edit', compact('store', 'product','categories','products','brands','publish_date','publish_time','option_sets','taxes','attributes', 'shipping_classes'));
    }

    public function duplicate(Product $product)
    {
        $qty_threshold = $product->stock ? $product->stock->out_of_stock : 0;
        $product = $this->copyProduct($product);
        $this->saveStock($product, $qty_threshold);
        return redirect()->route('product.edit', $product);
    }

    protected function copyProduct(Product $product)
    {
        $copy = new Product;
        $copy->name = 'Copy of '.$product->name;
        $copy->slug = $copy->freshTimestamp()->format('u');
        $copy->short_description = $product->short_description;
        $copy->description = $product->description;
        $copy->meta_title = $product->meta_title;
        $copy->meta_description = $product->meta_description;
        $copy->meta_keywords = $product->meta_keywords;

        if(!empty($product->brand)) {
            
            $copy->brand()->associate($product->brand);
            
        }

        if($product->optionSet) {
            $copy->optionSet()->associate($product->optionSet);
        }

        if($product->shippingClass) {
            $copy->shippingClass()->associate($product->shippingClass);
        }

        $copy->cost_price = $product->cost_price;
        $copy->selling_price = $product->selling_price;
        $copy->special_price = $product->special_price;
        $copy->special_active_on = $product->special_active_on;
        $copy->special_end_on = $product->special_end_on;
        $copy->online = $product->online;
        $copy->sku = $product->sku;
        $copy->barcode = $product->barcode;
        $copy->isbn = $product->isbn;
        $copy->upc = $product->upc;
        $copy->minimal_quantity = $product->minimal_quantity;
        $copy->width = $product->width;
        $copy->height = $product->height;
        $copy->depth = $product->depth;
        $copy->weight = $product->weight;
        $copy->type = $product->type;
        $copy->tags = $product->tags;
        $copy->condition = $product->condition;
        $copy->show_condition = $product->show_condition;
        $copy->available_for_order = $product->available_for_order;
        $copy->pre_order = $product->pre_order;

        if(!empty($product->available_date)) {
            $copy->available_date = $product->available_date;
        }

        $copy->available_now = $product->available_now;
        $copy->available_later = $product->available_later;  

        if(!empty($product->taxClass)) {

            $copy->taxClass()->associate($product->taxClass);
            
        } 

        $copy->publish_on = $product->publish_on;
        $copy->active = $product->active;
        $copy->state = 1;
        $copy->save();

        $copy->categories()->attach($product->categories->pluck('id'));

        if($product->images->count()) {
            $this->copyProductImages($product, $copy);
        }

        if($product->relatedProducts->count()) {
            $copy->relatedProducts()->syncWithoutDetaching($product->relatedProducts->pluck('id'));
        }

        if($product->variations->count()) {
            $this->copyProductAttribute($copy, $product->variations);
        } 

        if($product->file) {
            $this->copyProductFile($product->file, $copy);
        }

        return $copy;
    }

    protected function copyProductFile(File $file, $copy)
    {
        $product_file = new File;
        $product_file->product()->associate($copy);
        $product_file->filename = $file->filename;
        $product_file->size = $file->size;
        $product_file->maximum_downloads = $file->maximum_downloads;
        $product_file->active = $file->active;
        $product_file->created_at_tz = $file->created_at_tz;
        $product_file->updated_at_tz = $file->updated_at_tz;
        $product_file->save();
    }

    protected function copyProductImages(Product $product, Product $copy)
    {
        foreach ($product->images as $image) {
            $product_image = new ProductImage;
            $product_image->product()->associate($copy);

            if($image->productAttribute) {
                $product_image->productAttribute()->associate($image->productAttribute);
            }

            $product_image->store()->associate($product->store);
            $product_image->cart_default = $image->cart_default;
            $product_image->home_default = $image->home_default;
            $product_image->small_default = $image->small_default;
            $product_image->medium_default = $image->medium_default;
            $product_image->large_default = $image->large_default;
            $product_image->alt_text = $image->alt_text;
            $product_image->cover = $image->cover;
            $product_image->sort_order = $image->sort_order;
            $product_image->created_at_tz = $image->created_at_tz;
            $product_image->updated_at_tz = $image->updated_at_tz;
            $product_image->save();
        }
    }

    protected function copyProductAttribute(Product $product, $attributes)
    {
        foreach ($attributes as $attribute) {
            $product_attribute = new ProductAttribute;
            $product_attribute->product()->associate($product);
            $product_attribute->cost_price = $attribute->cost_price;
            $product_attribute->selling_price = $attribute->selling_price;
            $product_attribute->special_price = $attribute->special_price;
            $product_attribute->special_active_on = $attribute->special_active_on;
            $product_attribute->special_end_on = $attribute->special_end_on;
            $product_attribute->sku = $attribute->sku;
            $product_attribute->barcode = $attribute->barcode;
            $product_attribute->isbn = $attribute->isbn;
            $product_attribute->upc = $attribute->upc;
            $product_attribute->width = $attribute->width;
            $product_attribute->height = $attribute->height;
            $product_attribute->depth = $attribute->depth;
            $product_attribute->weight = $attribute->weight;
            $product_attribute->available_for_order = $attribute->available_for_order;
            $product_attribute->pre_order = $attribute->pre_order;
            $product_attribute->available_date = $attribute->available_date;
            $product_attribute->created_at_tz = $attribute->created_at_tz;
            $product_attribute->updated_at_tz = $attribute->updated_at_tz;
            $product_attribute->save();

            foreach($attribute->combinations as $value) {
                $combination = new Combination;
                $combination->productAttribute()->associate($product_attribute);
                $combination->option()->associate($value->option_id);
                $combination->save();
            }
        }   
    }

    /**
     * Update the specified resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function update(ProductUpdateFormRequest $request, Product $product)
    {
        $this->authorize('touch', $product);
        if(!empty($request->brand)) {
            
            $product->brand()->associate($request->brand);
            
        }

        if($request->option_set) {
            $product->optionSet()->associate($request->option_set);
        }

        if($request->shipping_class) {
            $product->shippingClass()->associate($request->shipping_class);
        }

        $product->name = $request->title;
        $product->slug = str_slug($request->url_handle, '-');
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->meta_title = $request->meta_title;
        $product->meta_description = $request->meta_description;
        $product->meta_keywords = $request->meta_keywords;

        if(!empty($request->cost_price)) {
            $product->cost_price = $this->getFormattedPrice($request->cost_price);
        }
       
        $product->selling_price = $this->getFormattedPrice($request->selling_price);

        if(!empty($request->special_price)) {
            $product->special_price = $this->getFormattedPrice($request->special_price);
        }
        
        if(!empty($request->special_start_date)) {
            $product->special_active_on = $request->special_start_date.' '.$this->getFormattedTime($request->special_start_time);   
        }

        if(!empty($request->special_end_date)) {
            $product->special_end_on = $request->special_end_date.' '.$this->getFormattedTime($request->special_end_time);   
        }

        if(!empty($request->online)) {
            $product->online = 1;
        }

        $product->sku = $request->sku;
        $product->barcode = $request->barcode;
        $product->isbn = $request->isbn;
        $product->upc = $request->upc;
        $product->minimal_quantity = $request->min_qty;
        $product->width = (float) str_replace(",","",$request->width);
        $product->height = (float) str_replace(",","",$request->height);
        $product->depth = (float) str_replace(",","",$request->depth);
        $product->weight = (float) str_replace(",","",$request->weight);
        $product->type = $request->product_type;
        $product->tags = $request->tags;
        $product->condition = $request->condition;
        $product->show_condition = $request->show_condition ? 1 : 0;

        if(!empty($request->online)) {
            $product->online = 1;
        }

        if($request->product_availability === 'preorder') {
            $product->pre_order = 1;
            $product->available_for_order = 0;
        } elseif($request->product_availability === 'backorder' || !$request->product_availability) {
            $product->pre_order = 0;
            $product->available_for_order = 1;
            $request->available_date = null;
        } elseif($request->product_availability === 'none') {
            $product->pre_order = 0;
            $product->available_for_order = 0;
            $request->available_date = null;
        }

        if(!empty($request->available_date)) {
            $product->available_date = $request->available_date;
        } elseif(!empty($product->available_date)) {
            $product->available_date = null;
        }

        $product->available_now = $request->instock;
        $product->available_later = $request->outofstock;  

        if(!empty($request->tax_class)) {

            $product->taxClass()->associate($request->tax_class);
            
        } 

        $product->active = $request->live;
        $product->save();
        $this->detachCategories($product, $product->categories);
        $product->categories()->attach(explode(',', $request->category));

        if(count($request->variation)) {
            $variations = $request->variation;
            for ($i=0; $i < count($variations); $i++) { 
                $this->processProductAttribute($product, $variations[$i], $request->tenant());
            }
        } 

        if($request->product_type == 'virtual') {
            if($request->file('product_file') != null) {
                $this->uploadProductFile($request, $product);
            }
        }

        if(!empty($request->related_products)) {
            $this->removeRelatedProducts($product);
            $product->relatedProducts()->syncWithoutDetaching($request->related_products);
        }

        if($product->type == 'variant') {

            $this->updateStock($product, $request->low_qty);
        }
        
        return redirect()->route('product.list')->withSuccess('Product updated successfully!');
    }

    protected function removeRelatedProducts(Product $product)
    {
        if($product->relatedProducts->count()) {
            foreach($product->relatedProducts as $related_product) {
                $product->relatedProducts()->detach($related_product->id);
            }
        }
    }

    protected function detachCategories(Product $product, $categories)
    {
        foreach($categories as $category) {
            $product->categories()->detach($category->id);
        }
    }

    protected function getAttributeOptions($attributes)
    { 
        $options = [];

        foreach($attributes as $attribute) {

            foreach ($attribute->options as $option) { 
                $options[$attribute->id][$option->id] = $option->id;
            
            }

        }

        return $options;
    }

    public function attributesGeneratorAction(Request $request, Product $product)
    { 
        $option_set = OptionSet::find($request->option_set);
        $product->optionSet()->associate($option_set);
        $product->save();
        $options = $this->getAttributeOptions($option_set->attributes);
        $this->processGenerate($product, $options);
        return response()->json($this->getProductAttributes($product->variations));
    }

    protected function processGenerate(Product $product, $options)
    {
        $combinations = array_values($this->createCombinations(array_values($options)));
        $combinationsValues = array_values(array_map(function () use ($product) {
            return array(
                'product_id' => $product->id
            );
        }, $combinations));
        $this->generateMultipleCombinations($combinationsValues, $combinations);
    }

    protected function generateMultipleCombinations($combinations, $attributes)
    {
     
        $res = true;
        foreach ($combinations as $key => $combination) {
        
           $product_attribute = new ProductAttribute;
           

            foreach ($combination as $field => $value) {
                $product_attribute->product()->associate($value);
            }
            $product_attribute->save();

            foreach ($attributes[$key] as $id_attribute) { 
                $product_combination = new Combination;
                $product_combination->productAttribute()->associate($product_attribute->id);
                $product_combination->option_id = $id_attribute;
                $product_combination->save();
            }
     
        }

        return $res;
    }

    protected function createCombinations($list)
    {
        if (count($list) <= 1) {
            return count($list) ? array_map(create_function('$v', 'return (array($v));'), $list[0]) : $list;
        }
        $res = array();
        $first = array_pop($list);
        foreach ($first as $attribute) {
            $tab = $this->createCombinations($list);
            foreach ($tab as $to_add) {
                $res[] = is_array($to_add) ? array_merge($to_add, array($attribute)) : array($to_add, $attribute);
            }
        }
        return $res;
    }

    public function updateVariations (ProductVariationsFormRequest $request, Product $product)
    {
        $variations = $request->variation;
   
        for ($i=0; $i < count($variations); $i++) { 
            $product_attribute = ProductAttribute::find($variations[$i]['id']);

            if($variations[$i]['cost_price']) {
                $product_attribute->cost_price = $variations[$i]['cost_price'];
            }

            if($variations[$i]['selling_price']) {
                $product_attribute->selling_price = $variations[$i]['selling_price'];
            }
            
            if($variations[$i]['special_price']) {
                $product_attribute->special_price = $variations[$i]['special_price'];
            }

            if($variations[$i]['special_active_date'] && $variations[$i]['special_active_time'] && $variations[$i]['special_end_date'] && $variations[$i]['special_end_time']) {

                $product_attribute->special_active_on = $variations[$i]['special_active_date'].' '.$this->getFormattedTime($variations[$i]['special_active_time']);
                $product_attribute->special_end_on = $variations[$i]['special_end_date'].' '.$this->getFormattedTime($variations[$i]['special_end_time']);
            }

            $product_attribute->save();
        }
        return response()->json($this->getProductAttributes($product->variations));
    }

    public function updateVariation (ProductVariationFormRequest $request, Product $product)
    {
        $variation = json_decode($request->variation, true);

        $product_attribute = ProductAttribute::find($variation['id']);
        $product_attribute->sku = $variation['sku'];

        if($variation['cost_price']) {
            $product_attribute->cost_price = $variation['cost_price'];
        }

        if($variation['selling_price']) {
            $product_attribute->selling_price = $variation['selling_price'];
        }

        if($variation['special_price']) {
            $product_attribute->special_price = $variation['special_price'];
        }

        if($variation['special_active_date'] && $variation['special_active_time'] && $variation['special_end_date'] && $variation['special_end_time']) {

            $product_attribute->special_active_on = $variation['special_active_date'].' '.$this->getFormattedTime($variation['special_active_time']);
            $product_attribute->special_end_on = $variation['special_end_date'].' '.$this->getFormattedTime($variation['special_end_time']);
        }
        
        $product_attribute->barcode = $variation['barcode'];
        $product_attribute->isbn = $variation['isbn'];
        $product_attribute->upc = $variation['upc'];

        if($variation['width']) {
            $product_attribute->width = $variation['width'];
        }

        if($variation['height']) {
            $product_attribute->height = $variation['height']; 
        }

        if($variation['depth']) {
            $product_attribute->depth = $variation['depth'];
        }

        if($variation['weight']) {
            $product_attribute->weight = $variation['weight'];
        }

        $product_attribute->save();

        if($request->file('image') != '') { 
            $this->uploadVariantImage($request->file('image'), $product_attribute);
        }

        return response()->json($this->getProductAttributes($product->variations));
    }

    public function destroyVariation(Product $product, ProductAttribute $product_attribute)
    {
        $product_attribute->delete();

        if($product_attribute->image) {

            unlinkFile(public_path('stores').'/'.session('store')->domain.'/product/'.$product_attribute->image->cart_default);
            unlinkFile(public_path('stores').'/'.session('store')->domain.'/product/'.$product_attribute->image->home_default);
            unlinkFile(public_path('stores').'/'.session('store')->domain.'/product/'.$product_attribute->image->small_default);
            unlinkFile(public_path('stores').'/'.session('store')->domain.'/product/'.$product_attribute->image->medium_default);
            unlinkFile(public_path('stores').'/'.session('store')->domain.'/product/'.$product_attribute->image->large_default);
        
        }
        
        return response()->json($this->getProductAttributes($product->variations));
    }

    public function destroyVariations(Product $product, $attributes)
    {
        $attributes = explode(',',$attributes);

        for ($i=0; $i < count($attributes); $i++) { 
            $product_attribute = ProductAttribute::find($attributes[$i]);

            if($product_attribute) {
                $product_attribute->delete();
            }
            
            if($product_attribute && $product_attribute->image) {
                unlinkFile(public_path('stores').'/'.session('store')->domain.'/product/'.$product_attribute->image->cart_default);
                unlinkFile(public_path('stores').'/'.session('store')->domain.'/product/'.$product_attribute->image->home_default);
                unlinkFile(public_path('stores').'/'.session('store')->domain.'/product/'.$product_attribute->image->small_default);
                unlinkFile(public_path('stores').'/'.session('store')->domain.'/product/'.$product_attribute->image->medium_default);
                unlinkFile(public_path('stores').'/'.session('store')->domain.'/product/'.$product_attribute->image->large_default);
            }

        }
        
        return response()->json($this->getProductAttributes($product->variations));
    }

    public function deleteVariantImage(Product $product, ProductAttribute $product_attribute)
    {
        unlinkFile(public_path('stores').'/'.session('store')->domain.'/product/'.$product_attribute->image->cart_default);
        unlinkFile(public_path('stores').'/'.session('store')->domain.'/product/'.$product_attribute->image->home_default);
        unlinkFile(public_path('stores').'/'.session('store')->domain.'/product/'.$product_attribute->image->small_default);
        unlinkFile(public_path('stores').'/'.session('store')->domain.'/product/'.$product_attribute->image->medium_default);
        unlinkFile(public_path('stores').'/'.session('store')->domain.'/product/'.$product_attribute->image->large_default);
        $product_attribute->image->delete();

        return response()->json($this->getProductAttributes($product->variations));
    }

    public function saveCategory(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique_to_store:categories,name',
            'parent_category' => 'required|numeric',
        ],[
            'parent_category' => 'Please choose a parent caegory.',
            'name.unique_to_store' => 'Category already exists.'
        ]);

        $category = new Category;
        if(!empty($request->parent_category)) {
            $category->parent()->associate($request->parent_category);
        }

        $category->name = $request->name;
        $category->store()->associate($request->tenant());
        $category->slug = str_slug($request->name,'-');
        $category->status = 1;
        $category->save();

    }

    public function saveBrand(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique_to_store:brands,name|max:255'
        ],[
            'name.unique_to_store' => 'Brand already exists.'
        ]);

        $brand = Brand::create([
            'name' => $request->name,
            'slug' => str_slug($request->name, '-'),
            'meta_title' => $request->name
        ]);

        $brands = $this->getBrands($request->tenant()->brands, $brand->id);
        
        return response()->json(compact('brands'));

    }

    protected function getBrands($brands, $selected)
    {
        $options = '<option value="">Choose brand</option>';

        foreach ($brands as $brand) {

            if($selected === $brand->id) {

                $options.= '<option value="'.$brand->id.'" selected>'.$brand->name.'</option>';

            } else {

                $options.= '<option value="'.$brand->id.'">'.$brand->name.'</option>';

            }
            
        }

        return $options;
    }

    public function saveTaxClass(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique_to_store:tax_classes,name|max:255'
        ],[
            'name.unique_to_store' => 'Tax Class already exists.'
        ]);

        $class = TaxClass::create([
                    'name' => $request->name
                ]);

        $classes = $this->getTaxClasses($request->tenant()->taxClasses, $class->id);
        
        return response()->json(compact('classes'));

    }

    protected function getTaxClasses($classes, $selected)
    {
        $options = '';

        foreach ($classes as $class) {

            if($selected === $class->id) {

                $options.= '<option value="'.$class->id.'" selected>'.$class->name.'</option>';

            } else {

                $options.= '<option value="'.$class->id.'">'.$class->name.'</option>';

            }
            
        }

        return $options;
    }

    public function saveShippingClass(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique_to_store:shipping_classes,name|max:255'
        ],[
            'name.unique_to_store' => 'Shipping Class already exists.'
        ]);

        $class = ShippingClass::create([
                    'name' => $request->name,
                    'slug' => str_slug($request->name, '-')
                ]);

        $classes = $this->getShippingClasses($request->tenant()->shippingClasses, $class->id);
        
        return response()->json(compact('classes'));

    }

    protected function getShippingClasses($classes, $selected)
    {
        $options = '';

        foreach ($classes as $class) {

            if($selected === $class->id) {

                $options.= '<option value="'.$class->id.'" selected>'.$class->name.'</option>';

            } else {

                $options.= '<option value="'.$class->id.'">'.$class->name.'</option>';

            }
            
        }

        return $options;
    }

    protected function getCategories(Request $request) 
    {
        $items = [];
        $category = request()->tenant()->categories->where('is_root_category', 1)->first();
        $items[0]['key'] = $category->id;
        $items[0]['title'] = $category->name;
        $items[0]['isFolder'] = true;
        $items[0]['expand'] = true;

        $items = $this->buildSubCategory($items[0], $category->children);
        
        return json_encode($items);
    }

    protected function buildSubCategory($item, $categories, Product $product = null)
    {
        for ($i=0; $i < count($categories); $i++) { 
            $item['children'][$i]['key'] = $categories[$i]->id;
            $item['children'][$i]['title'] = $categories[$i]->name;
            $item['children'][$i]['isFolder'] = true;
            $item['children'][$i]['expand'] = true;

            if($product && $product->categories()->where('id', $categories[$i]->id)->count()) {
                $item['children'][$i]['select'] = true;
            }

            if($categories[$i]->children->count()) {
                $item['children'][$i] = $this->buildSubCategory($item['children'][$i], $categories[$i]->children);
            }
        }
  
        return $item;
    }

    protected function getProductCategories(Request $request, Product $product) 
    {
        $items = [];
        $category = request()->tenant()->categories->where('is_root_category', 1)->first();
        $categories = $category->children;

        for($i=0; $i<count($categories); $i++) { 
            $items[$i]['key'] = $categories[$i]->id;
            $items[$i]['title'] = $categories[$i]->name;
            $items[$i]['isFolder'] = true;
            $items[$i]['expand'] = true;
            
            if($product->categories()->where('id', $categories[$i]->id)->count()) {
                $items[$i]['select'] = true;
            }
            

            if($categories[$i]->children->count()) {
                $items[$i] = $this->buildSubCategory($items[$i], $categories[$i]->children, $product); 
            }
        }
        
        return json_encode($items);
    }

    public function validateCategory(Request $request)
    {
        $result = Category::where('store_id', $request->tenant()->id)->where('name', $request->value)->count();

        return response()->json([
            'data' => $result
        ]);
    }

    protected function getProductAttributes($attributes)
    {

        return  fractal()
                ->collection($attributes)
                ->transformWith(new ProductVariationCollectionTransformer)
                ->toArray();
    }

    public function downloadFile(Request $request, File $file)
    {
        return response()->download(storage_path('app/stores/'.$request->tenant()->store_name.'/files/'.$file->id.'/'.$file->filename));
    }

}
