<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;

class OrderInvoiceTax extends Model
{
    protected $fillable = ['rate', 'amount'];

    public $timestamps = false;

    public function invoice()
    {
        return $this->belongsTo(OrderInvoice::class, 'order_invoice_id');
    }

    public function tax()
    {
    	return $this->belongsTo('Modules\Product\Entities\Tax');
    }
}
