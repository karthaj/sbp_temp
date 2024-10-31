<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    protected $fillable = ['product_name','product_quantity','product_quantity_refunded','product_price','product_sku','product_barcode','product_isbn','product_upc','product_weight','total_price','unit_price','original_product_price','original_cost_price'];

    public $timestamps = false;

    public function order()
    {
    	return $this->belongsTo(Order::class);
    }

    public function invoice()
    {
    	return $this->belongsTo(OrderInvoice::class, 'order_invoice_id');
    }

    public function store()
    {
    	return $this->belongsTo('Shopbox\Models\Zpanel\Store');
    }

    public function product()
    {
    	return $this->belongsTo('Modules\Product\Entities\Product')->withTrashed();
    }

    public function product_attribute()
    {
    	return $this->belongsTo('Modules\Product\Entities\ProductAttribute');
    }

    public function taxes()
    {
        return $this->hasMany(OrderDetailTax::class);
    }

    public function return_detail()
    {
        return $this->hasOne(OrderReturnDetail::class);
    }
}
