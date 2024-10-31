<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;

class CartDiscount extends Model
{
    protected $fillable = ['cart_id', 'discount_id'];

    public $timestamps = false;

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function discount()
    {
        return $this->belongsTo('Modules\Discount\Entities\Discount');
    }
}
