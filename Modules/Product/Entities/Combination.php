<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;

class Combination extends Model
{
    protected $fillable = ['product_attribute_id', 'option_id'];

    protected $table = 'product_attribute_combinations';

    public $timestamps = false;

    public function productAttribute()
    {
        return $this->belongsTo(ProductAttribute::class);
    }

    public function option()
    {
        return $this->belongsTo(Option::class);
    }

}
