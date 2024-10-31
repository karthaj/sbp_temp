<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;

class OrderInvoice extends Model
{
    protected $fillable = ['number','total_discount','total_paid_tax_excl','total_paid_tax_incl','total_products','total_products_wt','total_shipping_tax_excl','total_shipping_tax_incl','created_at_tz','created_at'];

    public $timestamps = false;

    public function order()
    {
    	return $this->belongsTo(Order::class);
    }

    public function taxes()
    {
    	return $this->hasMany(OrderInvoiceTax::class);
    }
}
