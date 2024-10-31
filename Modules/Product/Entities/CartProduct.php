<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;

class CartProduct extends Model
{
    protected $fillable = ['quantity'];

    public function store()
    {
        return $this->belongsTo('Shopbox\Models\Zpanel\Store');
    }

    public function product()
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function product_attribute()
    {
        return $this->belongsTo(ProductAttribute::class);
    }

    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    public function stock()
    {
        return $this->belongsTo(StoreStock::class, 'store_stock_id');
    }
}
