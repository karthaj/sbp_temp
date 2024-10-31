<?php

namespace Modules\Product\Entities;

use Laravel\Scout\Searchable;
use Shopbox\Tenant\Traits\ForTenants;
use Modules\Product\Traits\HasPrice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{

    use ForTenants, SoftDeletes, Searchable, HasPrice;

    public $asYouType = true;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'cost_price',
        'selling_price',
        'special_price',
        'special_active_on',
        'special_end_on',
        'barcode',
        'isbn',
        'upc',
        'width',
        'height',
        'depth',
        'weight',
        'customizable',
        'on_sale',
        'condition',
        'show_condition',
        'available_for_order',
        'pre_order',
        'available_date',
        'track_inventory',
        'publish_on',
        'active',
        'state',
        'online'
    ];

    protected $dates = [
        'publish_on',
        'available_date',
        'special_active_on',
        'special_end_on',
    ];


      /**
     * Get the index name for the model.
     *
     * @return string
     */

    public function searchableAs()
    {
        return 'products';
    }

    public function toSearchableArray()
    {
        $array = $this->toArray();

        $data = [];
        $data['id'] = $array['id']; 
        $data['name'] = $array['name']; 
        $data['brand'] = $this->brand ? $this->brand->name : ''; 
        $data['tags'] = $array['tags']; 
        
        if($this->variations->count()) {

            foreach($this->variations as $variation) {

                $data['sku'] =  $variation->sku ? $variation->sku : $array['sku'];       
                $data['barcode'] =  $variation->barcode ? $variation->barcode : $array['barcode'];       
                $data['isbn'] =  $variation->isbn ? $variation->isbn : $array['isbn'];       
                $data['upc'] =  $variation->upc ? $variation->upc : $array['upc'];  

            }

        } else {
            
            $data['sku'] =  $array['sku'];       
            $data['barcode'] =  $array['barcode'];       
            $data['isbn'] =  $array['isbn'];       
            $data['upc'] =  $array['upc']; 

        }    

        return $data;

    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function scopeFinished(Builder $builder)
    {
        return $builder->where('state', 1);
    }

    public function store()
    {
        return $this->belongsTo('Shopbox\Models\Zpanel\Store');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->whereNull('product_attribute_id')->orderBy('sort_order');
    }

    public function features()
    {
        return $this->hasMany(Feature::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function variations()
    {
        return $this->hasMany(ProductAttribute::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function optionSet()
    {
        return $this->belongsTo(OptionSet::class);
    }

    public function file()
    {
        return $this->hasOne(File::class);
    }

    public function taxClass()
    {
        return $this->belongsTo(TaxClass::class);
    }

    public function shippingClass()
    {
        return $this->belongsTo(ShippingClass::class);
    }

    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    public function relatedProducts()
    {
        return $this->belongsToMany(Product::class, 'related_products', 'related_product_id');
    }

    public function getImages() 
    {
        $data = [];

        foreach ($this->images as $key => $image) {
            if($image->cover) {
                $cover['id'] = $image->id;
                $cover['alt_text'] = $image->alt_text;
                $cover['cover'] = (bool) $image->cover;
                $cover['cart_default'] = asset('stores').'/'.$this->store->domain.'/product/'.$image->cart_default;
                $cover['home_default'] = asset('stores').'/'.$this->store->domain.'/product/'.$image->home_default;
                $cover['small_default'] = asset('stores').'/'.$this->store->domain.'/product/'.$image->small_default;
                $cover['medium_default'] = asset('stores').'/'.$this->store->domain.'/product/'.$image->medium_default;
                $cover['large_default'] = asset('stores').'/'.$this->store->domain.'/product/'.$image->large_default;
                $data = array_prepend($data, $cover);
            } else {
                $data[$key]['id'] = $image->id;
                $data[$key]['alt_text'] = $image->alt_text;
                $data[$key]['cover'] = (bool) $image->cover;
                $data[$key]['cart_default'] = asset('stores').'/'.$this->store->domain.'/product/'.$image->cart_default;
                $data[$key]['home_default'] = asset('stores').'/'.$this->store->domain.'/product/'.$image->home_default;
                $data[$key]['small_default'] = asset('stores').'/'.$this->store->domain.'/product/'.$image->small_default;
                $data[$key]['medium_default'] = asset('stores').'/'.$this->store->domain.'/product/'.$image->medium_default;
                $data[$key]['large_default'] = asset('stores').'/'.$this->store->domain.'/product/'.$image->large_default;
            }
        }

        return $data;
    }

    public function getCategories()
    {
        $data = [];

        if($this->categories->count()) {
            foreach ($this->categories as $key => $category) {
                $data[$key]['id'] = $category->id;
                $data[$key]['name'] = $category->name;
                $data[$key]['url'] = route('stores.categories.category', $category);
            }
        }
        return $data;
    }

    public function image () 
    {
        if($this->images->count()) {

            if($this->images->where('cover', 1)->count()) {
                return $this->images->where('cover', 1)->first()->medium_default;
            }

        }

        return '';
    }
}
