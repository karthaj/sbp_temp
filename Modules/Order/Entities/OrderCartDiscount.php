<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;

class OrderCartDiscount extends Model
{
    protected $fillable = ['name','value','free_shipping'];

    public $timestamps = false;

    public function order()
    {
    	return $this->belongsTo(Order::class);
    }

    public function discount()
    {
    	return $this->belongsTo('Modules\Discount\Entities\Discount');
    }

    public function invoice()
    {
    	return $this->belongsTo(OrderInvoice::class, 'order_invoice_id');
    }
}
