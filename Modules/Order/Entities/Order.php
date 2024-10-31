<?php

namespace Modules\Order\Entities;

use Shopbox\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Shopbox\Filters\Report\ReportFilters;
use Shopbox\Filters\Report\Sales\SaleFilters;
use Modules\Order\Filters\Order\OrderFilters;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use ForTenants, SoftDeletes;

    protected $fillable = ['reference','order_source','payment','shipping_module','total_discounts','total_paid','total_paid_tax_incl','total_paid_tax_excl','total_products','total_products_wt','total_shipping','total_shipping_tax_incl','total_shipping_tax_excl','invoice_number','invoice_date','invoice_date_tz', 'surcharge', 'status', 'archived'];

    protected $dates = ['created_at_tz', 'updated_at_tz'];

    public function getRouteKeyName()
    {
        return 'reference';
    }

    public function scopeFilter(Builder $builder, $request, array $filters = [])
    {
        return (new ReportFilters($request))->add($filters)->filter($builder);
    }

    public function scopeSale(Builder $builder, $request, array $filters = [])
    {
        return (new SaleFilters($request))->add($filters)->filter($builder);
    }

    public function scopeFilterBy(Builder $builder, $request, array $filters = [])
    {
        return (new OrderFilters($request))->add($filters)->filter($builder);
    }

    public function getFormattedOrderIdAttribute()
    {
        return $this->store_id.'-'.$this->order_id;
    }

    public function carrier()
    {
    	return $this->belongsTo('Modules\Product\Entities\ShippingZoneMethod', 'carrier_id');
    }

    public function store()
    {
    	return $this->belongsTo('Shopbox\Models\Zpanel\Store');
    }

    public function customer()
    {
    	return $this->belongsTo('Modules\Customer\Entities\Customer');
    }

    public function cart()
    {
    	return $this->belongsTo('Modules\Product\Entities\Cart')->withTrashed();
    }

    public function currency()
    {
    	return $this->belongsTo('Shopbox\Models\Zpanel\Currency');
    }

    public function shipping_address()
    {
    	return $this->belongsTo('Modules\Customer\Entities\Address', 'shipping_address_id')->withTrashed();
    }

    public function billing_address()
    {
    	return $this->belongsTo('Modules\Customer\Entities\Address', 'billing_address_id')->withTrashed();
    }

    public function state()
    {
    	return $this->belongsTo(OrderState::class, 'current_state');
    }

    public function details()
    {
    	return $this->hasMany(OrderDetail::class);
    }

    public function history()
    {
    	return $this->hasMany(OrderHistory::class);
    }

    public function invoice()
    {
        return $this->hasOne(OrderInvoice::class);
    }

    public function cart_discount()
    {
        return $this->hasOne(OrderCartDiscount::class);
    }

    public function payment()
    {
        return $this->hasOne(OrderPayment::class);
    }

    public function shipper()
    {
        return $this->hasOne(OrderCarrier::class);
    }

    public function messages()
    {
        return $this->hasMany(OrderMessage::class);
    }

    public function requiredShipping()
    {
        foreach ($this->details as $detail) {
            if($detail->product->type != 'virtual') {
                return true;
            }
        }

        return false;
    }

}
