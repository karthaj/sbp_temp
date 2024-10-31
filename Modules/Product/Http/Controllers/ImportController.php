<?php

namespace Modules\Product\Http\Controllers;

use Excel;
use Jenssegers\Agent\Agent;
use Modules\Product\Traits\ValidateImportTrait;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Shopbox\Models\Zpanel\Store;
use Modules\Product\Entities\Product;
use Modules\Product\Entities\ProductImage;
use Modules\Product\Entities\ProductAttribute;
use Modules\Product\Entities\Combination;
use Modules\Product\Entities\Attribute;
use Modules\Product\Entities\Option;
use Modules\Product\Entities\OptionSet;
use Modules\Product\Entities\File;
use Modules\Product\Entities\Stock;
use Modules\Product\Traits\StockTrait;
use Modules\Product\Http\Requests\Product\ImportFormRequest;


class ImportController extends Controller
{

    use ValidateImportTrait, StockTrait;

    protected $agent;

    public function __construct()     
    {
        $this->agent = new Agent();
    }

    protected $errors = [];
    protected $results = [];
    protected $successCount = 0;
    protected $errorCount = 0;
    protected $store = '';
    protected $index = 0;
    protected $product;
    protected $option_set;
    protected $headers = ['product_type', 'product_id', 'product_name', 'product_short_description', 'product_long_description', 'category',
                            'brand_name', 'product_sku', 'variant_set', 'cost_price', 'selling_price', 'product_image', 'product_tags',
                            'product_barcode', 'product_isbn', 'product_upc', 'product_weight', 'product_width', 'product_height',
                            'product_depth', 'product_visible', 'product_url', 'seo_title', 'seo_description', 'seo_keywords'];

    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        return view('product::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {   
        return view('product::products.import');
    }

    /**
     * Store a newly created resource in storage.
     * @param  Request $request
     * @return Response
     */
    public function store(ImportFormRequest $request)
    {   
        $errors = collect([]);

        if($request->file('importfile')->getClientOriginalExtension() !== 'csv') {
            $errors->push('File type not supported.');
            session()->flash('errors', $errors);
            return redirect()->back();
        }

        $this->store = $request->tenant();
          
        Excel::load($request->file('importfile')->getRealPath(), function($reader) use($request, $errors) {

            $columns = array_combine($reader->first()->keys()->toArray(), $reader->first()->keys()->toArray());
            if(!array_has($columns, $this->headers)) {

                $errors->push('Invalid CSV: Missing headers or contains invalid headers.');
                session()->flash('errors', $errors);
                return redirect()->back();

            }
         
            foreach ($reader->toArray() as $key => $row) { 
                $this->index += 1;
                $product = '';

                if($this->validateProductType($row['product_type'])) {
                    if($request->overwrite) {

                        if(is_numeric((int)$row['product_id'])) {

                            $product = $request->tenant()->products()->where('id', (int)$row['product_id'])->first();

                            if(empty($product)) {
                                break;
                            }
                    
                        } elseif($row['product_id'] == '')  {
                            break;
                        }

                    } else {
                        $product = new Product;
                    }
            
                    $product->type = $row['product_type'];
                    $product->name = $row['product_name'];
                    $this->option_set = $row['variant_set'];

                    if($row['product_type'] === 'variant') {
                        $option_set = $this->checkOptionSetExists($this->option_set);
       
                        if(!$option_set) {
                            $this->errors[$row['product_name']]['variant_set'] = 'Variant set not found.';
                        }
                    }

                } else {

                    if($row['product_type'] === 'v-product') {
                        // insert product variations.
                        $data = ['product_name' => $row['product_name'], 'cost_price' => $row['cost_price'], 'selling_price' => $row['selling_price'], 'stock' => $row['stock'], 'sku' => $row['product_sku'], 'barcode' => $row['product_barcode'], 'isbn' => $row['product_isbn'], 'upc' => $row['product_upc'], 'width' => $row['product_width'], 'height' => $row['product_height'], 'depth' => $row['product_depth'], 'weight' => $row['product_weight'], 'image' => $row['product_image']];


                        if($request->overwrite) {
                            $this->saveProductVariantions($data, (int)$row['product_id'], (int)$request->overwrite_image);
                        } else {
                            $this->saveProductVariantions($data);
                        }
                        continue;

                    } else {
                        $this->errors[$row['product_name']]['product_type'] = 'Product Type is not valid.';
                    }

                }

                if(!$product instanceof Product) {
                    $errors->push('CSV contains empty rows. Delete empty rows between records and try again.');
                    session()->flash('errors', $errors);
                    return redirect()->back();
                }

                $slug = $row['product_url'] ? str_slug($row['product_url'], '-') : str_slug($row['product_name'], '-');

                if($request->overwrite) {
                    $product->slug = $slug;
                } else {
                    if(unique_to_store(['products', 'slug'], $slug)) {
                        $product->slug = $slug;
                    } else {
                        $this->errors[$row['product_name']]['product_url'] = 'Product url already exists.';
                    }
                }
               
                if(!empty($row['product_short_description'])) {
                    $product->short_description = $row['product_short_description'];
                } else if($row['product_short_description'] === false) {
                    $this->errors[$row['product_name']]['product_short_description'] = 'Foreign character identified.';
                } 

                if(!empty($row['product_long_description'])) {
                    $product->description = $row['product_long_description'];
                } else if($row['product_long_description'] === false) {
                    $this->errors[$row['product_name']]['product_long_description'] = 'Foreign character identified.';
                }

                if($this->validatePrice($row['cost_price'], $row['product_name'])) {
                    $product->cost_price = $row['cost_price'];
                } 

                if($this->validatePrice($row['selling_price'], $row['product_name'])) {
                    $product->selling_price = $row['selling_price'];
                } 
                
                if(!$request->overwrite && $this->validateProductIdentifier(trim($row['product_sku']), 'sku', 'Product SKU must be unique.', $row['product_name'], 'product_sku', $product)) {
                    $product->sku = trim($row['product_sku']);
                } 

                if(!$request->overwrite && $this->validateProductIdentifier(trim($row['product_barcode']), 'barcode', 'Product Barcode must be unique.', $row['product_name'], 'product_barcode', $product)) {
                    $product->barcode = trim($row['product_barcode']);
                } 

                if(!$request->overwrite && $this->validateProductIdentifier(trim($row['product_isbn']), 'isbn', 'Product ISBN must be unique.', $row['product_name'], 'product_isbn', $product)) {
                    $product->isbn = trim($row['product_isbn']);
                } 

                if(!$request->overwrite && $this->validateProductIdentifier(trim($row['product_upc']), 'upc', 'Product UPC must be unique.', $row['product_name'], 'product_upc', $product)) {
                    $product->upc = $row['product_upc'];
                } 
                
                $product->minimal_quantity = 1;
                
                if(!empty($row['product_weight'])) {
                    if($this->validNumeric($row['product_weight'])) {
                        $product->weight = $row['product_weight'];
                    } else {
                        $this->errors[$row['product_name']]['product_weight'] = 'Product Weight must be a valid non-negative number.';
                    }
                } 
               
                if(!empty($row['product_width'])) {
                    if($this->validNumeric($row['product_width'])) {
                        $product->width = $row['product_width'];
                    } else {
                        $this->errors[$row['product_name']]['product_width'] = 'Product Width must be a valid non-negative number.';
                    }
                } 

                if(!empty($row['product_height'])) {
                    if($this->validNumeric($row['product_height'])) {
                        $product->height = $row['product_height'];
                    } else {
                        $this->errors[$row['product_name']]['product_height'] = 'Product Height must be a valid non-negative number.';
                    }
                } 

                if(!empty($row['product_depth'])) {
                    if($this->validNumeric($row['product_depth'])) {
                        $product->depth = $row['product_depth'];
                    } else {
                        $this->errors[$row['product_name']]['product_depth'] = 'Product Depth must be a valid non-negative number.';
                    }
                } 
                
                if(!empty($row['product_tags'])) {
                     $product->tags = $row['product_tags'];
                }
               
                $product->state = 1;
                if($row['product_visible'] === 'Y') {
                    $product->active = 1;
                } else if($row['product_visible'] === 'N') {
                    $product->active = 0;
                }              

                $product->publish_on = date('Y-m-d H:i:s');
                $product->taxClass()->associate($this->setDefaultTaxClass($this->store));

                if(!empty($row['brand_name'])) {
                   $product->brand()->associate($this->saveBrand($row['brand_name'], $this->store));
                }

                $product->meta_title = $row['seo_title'];
                $product->meta_description = $row['seo_description'];
                $product->meta_keywords = $row['seo_keywords'];

                if(empty($row['category'])) {
                    // $this->errors['product_name']['category'] = 'Please add atleast one category.';
                    $row['category'] = 'Unassigned';
                } elseif(!empty($row['category'])) {
                    if(!str_contains($row['category'], ';')) {
                        $this->errors[$row['product_name']]['category'] = 'Syntax error: Missing semi colon ";" in category column.'; 
                    } elseif(!ends_with($row['category'], ';')) {
                        $this->errors[$row['product_name']]['category'] = 'Syntax error: Missing semi colon ";" in category column.'; 
                    }
                }

                if(!is_numeric((int)$row['stock'])) {
                    $this->errors[$row['product_name']]['stock'] = 'Stock must be numeric.';
                }
               
                if(count($this->errors) == 0) {
                    $product->save();
                    
                    if($product->type != 'variant') {
                        $this->stockProducts($product, (int) $row['stock'], $request->overwrite);
                    } 
    
                    $product->categories()->syncWithoutDetaching($this->getCategories($row['category'], $this->store));

                    if(!empty($row['product_image'])) {
                        $this->saveImages($product, $product->store, $row['product_image'], (int)$request->overwrite_image);
                    }

                    if($row['product_type'] !== 'variant' && count($this->errors) == 0) {
                        $this->successCount += 1;
                    }
                
                } else {
                    if($row['product_type'] !== 'variant') {
                        $this->errorCount += 1;
                    }
                }

                $this->product = $product;
                
            }

        
        });

        $this->results['success'] = $this->successCount.' products imported successfully';
        $this->results['error'] = $this->errorCount.' products failed to be imported';

        session()->flash('results', $this->results);
        session()->flash('messages', $this->errors);
        
        return redirect()->route('product.import');
    }

    protected function stockProducts (Product $product, $qty = 0, $overwrite, ProductAttribute $attribute = null) 
    {
        if($overwrite && $attribute) {
            if($attribute->stock) 
                $stock = $attribute->stock;
            else 
                $stock = new Stock;
        } else if($overwrite && $product->stock) {
            if($product->stock)
                $stock = $product->stock;
            else 
                $stock = new Stock;
        } else {
             $stock = new Stock;
        }

        $stock->store()->associate($this->store);
        $stock->product()->associate($product);
        if($attribute) {
            $stock->productAttribute()->associate($attribute);
        }
        $stock->save();

        if($qty > 0) {
            $this->addStock([
                'stock_id' => $stock->id,
                'quantity' => $qty,
                'remarks' => ''
            ]);
        } else {
            if($this->store->storeLocations->count()) {
                foreach($this->store->storeLocations as $location) {
                    $this->incrementStock($stock, $qty, $location);
                }
            }
        }
        
    } 

    protected function saveImages(Product $product,Store $store, $images, $overwrite) 
    {
        $images = array_filter(explode(';', $images));

        if($overwrite) {
            foreach($product->images as $image) {
                unlinkFile(public_path('stores').'/'.$this->store->domain.'/product/'.$image->cart_default);
                unlinkFile(public_path('stores').'/'.$this->store->domain.'/product/'.$image->home_default);
                unlinkFile(public_path('stores').'/'.$this->store->domain.'/product/'.$image->small_default);
                unlinkFile(public_path('stores').'/'.$this->store->domain.'/product/'.$image->medium_default);
                unlinkFile(public_path('stores').'/'.$this->store->domain.'/product/'.$image->large_default);
                
                $image->delete();
            }
        }

        foreach ($images as $index => $image) {
            $product_image = new ProductImage;
            $product_image->product()->associate($product);
            $product_image->store()->associate($store);
            $product_image->cart_default = $image; 
            $product_image->home_default = $image; 
            $product_image->small_default = $image; 
            $product_image->medium_default = $image; 
            $product_image->large_default = $image; 

            if($index == 0)
                $product_image->cover = 1;

            $product_image->sort_order = $index + 1;
            $product_image->save();
        }
        
    }

    protected function saveProductVariantions($data, $attribute_id = null, $overwrite_image = null)
    {
        $options = explode(',', $data['product_name']);
        $store = $this->store;
        $attribute_value = '';
        $option_set = $this->checkOptionSetExists($this->option_set);
       
        if($this->option_set == '' || $option_set == null) {
            $this->errors[$this->product->name]['variant_set'] = 'Variant set not found.';
            $this->product->forceDelete();
            return;
        }

        if(!is_numeric((int)$data['stock'])) {
            $this->errors[$this->product->name]['stock'] = 'Stock must be numeric.';
        }
        //$option_set = $this->saveOptionSet($store, $this->option_set);

        $this->product->optionSet()->associate($option_set);
        $this->product->save();

        if($attribute_id != null) {
            $product_attribute = $this->saveProductVariation($data, $store, $attribute_id);
            if(!$product_attribute) {
                return;
            }
            $product_attribute->combinations()->delete();
        } else {
            $product_attribute = $this->saveProductVariation($data, $store);
        }

        if($attribute_id != null) {
            $this->stockProducts($product_attribute->product, (int) $data['stock'], 1, $product_attribute);
        } else {
            $this->stockProducts($product_attribute->product, (int) $data['stock'], 0, $product_attribute);
        }

        if($data['image']) {
            $overwrite = $overwrite_image ? true : false;
            $this->saveVariantImage($store, $product_attribute, $data['image'], $overwrite);
        }

        foreach($options as $option) {
            /*if(preg_match('/[CS]^[a-zA-Z]+$/', $option, $match)) {
                dd('match found = '.$option);
            } else {
                dd('match not found = '.$option);
            }*/
            $data = explode('=', $option);
            $type = substr($data[0], 0, strpos($option, ']') + 1);
            $attribute = substr($data[0], strpos($option, ']') + 1);
            $attribute = $this->saveAttribute($type, $attribute, $type, $option_set, $store);

            if($attribute == false) {
                $this->errors[$this->product->name]['attribute'] = 'The option name '.$attribute.' is already in use.';
                break;
            }

            if($type === '[CS]') {
                $option_value = explode(':', $data[1]);
                
                if(count($option_value) == 1) {
                    $this->errors[$this->product->name]['color'] = 'Invalid color swatch syntax.';
                    $this->successCount -= 1;
                    $this->errorCount += 1;
                    break;
                }

                if(starts_with($option_value[1], '#')) {

                    $attribute_value = $this->storeOptions($attribute, $option_value[0], $store, $option_value[1]);

                } else {
                    $extensions = ['jpg', 'jpeg', 'png'];
                    $extension = substr($option_value[1], strpos($option_value[1], '.') + 1);

                    if (!in_array($extension, $extensions)) {
                        $this->errors[$this->product->name]['pattern'] = 'Invalid pattern image.';
                        $this->successCount -= 1;
                        $this->errorCount += 1;
                        return false;
                    } 
                    
                    $attribute_value = $this->storeOptions($attribute, $texture[0], $store, null, $option_value[1]);
                }

            } else {
                
                $attribute_value = $this->storeOptions($attribute, $data[1], $store);
            }

            $combination = $this->getCombination($product_attribute, $attribute_value);

            if(!$combination) {
                $combination = new Combination;
            }

            $combination->productAttribute()->associate($product_attribute);
            $combination->option()->associate($attribute_value);
            $combination->save();


            // if(!$this->combinationExits($product_attribute, $attribute_value)) {
            //     $combination = new Combination;
            //     $combination->productAttribute()->associate($product_attribute);
            //     $combination->option()->associate($attribute_value);
            //     $combination->save();
            // }
            
        }

    }

    protected function saveVariantImage(Store $store, ProductAttribute $product_attribute, $image, $overwrite)
    {
        if($overwrite && $product_attribute) {
            $product_image =  $product_attribute->image;
        } else {
            $product_image = new ProductImage;
        }

        $product_image->product()->associate($product_attribute->product);
        $product_image->productAttribute()->associate($product_attribute);
        $product_image->store()->associate($store);
        $product_image->cart_default = $image; 
        $product_image->home_default = $image; 
        $product_image->small_default = $image; 
        $product_image->medium_default = $image; 
        $product_image->large_default = $image; 
        $product_image->sort_order = $product_attribute->product->images->count() + 1;
        $product_image->save();

    }

    protected function getCombination(ProductAttribute $product_attribute, Option $option)
    {
        return Combination::where('product_attribute_id', $product_attribute->id)->where('option_id', $option->id)->first();
    }

    protected function saveOptionSet($store, $option_set)
    {
        $data = $this->checkOptionSetExists($option_set);

        if($data === null) {
            $option_set = new OptionSet;
            $option_set->name = strtolower($this->option_set);
            $option_set->save();

            return $option_set;
        } 

        return $data;
    }

    protected function saveProductVariation($data, Store $store, $product_attribute_id = null)
    {
        $product_attribute = '';

        if($product_attribute_id) {
            $product_attribute = ProductAttribute::find($product_attribute_id);
            if(!$product_attribute) {
                $this->errors[$data['product_name']]['product_id'] = 'Variant ID '.$product_attribute_id.' not found.';
                $this->errorCount += 1;
                return;
            }
        } else {
            $product_attribute = new ProductAttribute;
        }
        
        $product_attribute->product()->associate($this->product);

        if($this->validatePrice($data['cost_price'], $data['product_name'])) {
            $product_attribute->cost_price = $data['cost_price'];
        } 

        if($this->validatePrice($data['selling_price'], $data['product_name'])) {
            $product_attribute->selling_price = $data['selling_price'];
        } 

        if($this->validateProductIdentifier(trim($data['sku']), 'sku', 'Product SKU must be unique.', $data['product_name'], 'sku', $this->product)) {
            $product_attribute->sku = trim($data['sku']);
        } 

        if($this->validateProductIdentifier(trim($data['barcode']), 'barcode', 'Product Barcode must be unique.', $data['product_name'], 'barcode', $this->product)) {
            $product_attribute->barcode = trim($data['barcode']);
        } 

        if($this->validateProductIdentifier(trim($data['isbn']), 'isbn', 'Product ISBN must be unique.', $data['product_name'], 'isbn', $this->product)) {
            $product_attribute->isbn = trim($data['isbn']);
        } 

        if($this->validateProductIdentifier(trim($data['upc']), 'upc', 'Product UPC must be unique.', $data['product_name'], 'upc', $this->product)) {
            $product_attribute->upc = $data['upc'];
        } 

        if(!empty($data['weight'])) {
            if($this->validNumeric($data['weight'])) {
                $product_attribute->weight = $data['weight'];
            } else {
                $this->errors[$data['product_name']]['weight'] = 'Product Weight must be a valid non-negative number.';
            }
        } 
       
        if(!empty($data['width'])) {
            if($this->validNumeric($data['width'])) {
                $product_attribute->width = $data['width'];
            } else {
                $this->errors[$data['product_name']]['width'] = 'Product Width must be a valid non-negative number.';
            }
        } 

        if(!empty($data['height'])) {
            if($this->validNumeric($data['height'])) {
                $product_attribute->height = $data['height'];
            } else {
                $this->errors[$data['product_name']]['height'] = 'Product Height must be a valid non-negative number.';
            }
        } 

        if(!empty($data['depth'])) {
            if($this->validNumeric($data['depth'])) {
                $product_attribute->depth = $data['depth'];
            } else {
                $this->errors[$data['product_name']]['depth'] = 'Product Depth must be a valid non-negative number.';
            }
        } 

        if($product_attribute_id) {
            $product_attribute->updated_at_tz = $product_attribute->freshTimestamp()->timezone($store->setting->timezone->timezone);
        } else {
            $product_attribute->created_at_tz = $product_attribute->freshTimestamp()->timezone($store->setting->timezone->timezone);
            $product_attribute->updated_at_tz = $product_attribute->freshTimestamp()->timezone($store->setting->timezone->timezone);
        }

        if($product_attribute->save()) {
             $this->successCount += 1;
         } else  {
             $this->errorCount += 1;
         }

        return $product_attribute;
    }

    protected function saveAttribute($type, $attribute, $style, OptionSet $option_set, Store $store) 
    {

        $display_style = ['[CS]', '[RT]', '[RB]', '[S]'];

        if (!in_array($style, $display_style)) {
            return false;
        } 

        $result = $this->checkAttributeExists($attribute, $option_set);

        if($result !== null) {
            return $result;
        } 

        /*if($this->checkAttributeAvailability($attribute)) {
            return false;
        }*/

        $attr = new Attribute;
        $attr->name = strtolower($attribute);
        $attr->public_name = strtolower($attribute);
        if ($style === '[CS]') {
            $attr->group_type = 'swatch';
        }
        $attr->display_style = $style;
        $attr->save();

        $attr->option_sets()->syncWithoutDetaching($option_set);

        return $attr;
    }

    protected function storeOptions($attribute, $option, $store, $hex_code = null, $pattern = null)
    {
        $data = $this->checkOptionExists($attribute, $option);

        if($data === null) {
            $value = new Option;
            $value->attribute()->associate($attribute);
            $value->name = strtolower($option);
            $value->sort_order = $this->getHighestOrder($attribute) + 1;
            if ($hex_code) {
                $value->color = $hex_code;
            }
            if ($pattern) {
                $value->pattern = '/'.$pattern;
            }
            $value->created_at_tz = $value->freshTimestamp()->timezone($store->setting->timezone->timezone);
            $value->updated_at_tz = $value->freshTimestamp()->timezone($store->setting->timezone->timezone);
            $value->save();

            return $value;
        } 

        return $data;
    }

    public function getHighestOrder($attribute)
    {  
        return (int) $attribute->options()->count();
    
    }

    protected function checkOptionExists($attribute, $option)
    {
        return $attribute->options()->where('name', $option)->first();
    }

    protected function checkAttributeExists($attribute, OptionSet $option_set)
    {
        return $option_set->attributes()->where('public_name', $attribute)->first();
    }

    protected function checkOptionSetExists($option_set)
    {
        return OptionSet::where('name', $option_set)->first();
    }

    protected function checkAttributeAvailability($attribute)
    {
        return (bool) Attribute::where('name', $attribute)->first();
    }

}
