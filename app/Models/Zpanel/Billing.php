<?php

namespace Shopbox\Models\Zpanel;

use Shopbox\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    use ForTenants;

    protected $fillable = ['amount', 'discount_amount', 'tax', 'reimburse', 'total_payable', 'state', 'reference'];

    public function getRouteKeyName()
    {
        return 'reference';
    }

    public function address()
    {
        return $this->hasOne(BillingAddress::class);
    }

    public function store()
    {
    	return $this->belongsTo(Store::class);
    }

    public function items()
    {
    	return $this->hasMany(BillingItem::class);
    }

    public function reminders()
    {
        return $this->hasMany(BillingReminder::class);
    }

    public function discount()
    {
        return $this->belongsTo('Modules\Discount\Entities\Discount');
    }

    public function invoice()
    {
        return $this->hasOne(BillingInvoice::class);
    }

}
