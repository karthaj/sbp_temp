<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Product\Traits\HasPrice;

class ProductAttribute extends Model
{
    use HasPrice;
    
    protected $fillable = ['cost_price','selling_price','special_price','special_active_on', 'special_end_on','sku','barcode','isbn','upc','minimal_quantity','width','height','depth','weight','available_for_order','pre_order','available_date'];

    protected $dates = ['special_active_on', 'special_end_on', 'available_date'];

    public function getFormattedPrice($value) 
    {;
        if($value == 0) {
            return $this->product->store->setting->currency->iso_code.' '.number_format($this->product->selling_price, 2);
        }

        return $this->product->store->setting->currency->iso_code.' '.number_format($value,2);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function combinations()
    {
        return $this->hasMany(Combination::class);
    }

    public function stock()
    {
        return $this->hasOne(Stock::class);
    }

    public function image()
    {
        return $this->hasOne(ProductImage::class);
    }
}
