<?php

namespace Modules\Product\Entities;

use Shopbox\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class TaxOption extends Model
{
	use ForTenants;

    protected $fillable = ['tax_label', 'charge_tax', 'price_includes_tax', 'tax_based_on', 'tax_display_product_listing', 'tax_display_product_page', 'tax_display_cart', 'tax_display_order_invoice', 'display_tax_charge_cart', 'display_tax_charge_order'];

    public function store () {
        return $this->belongsTo('Shopbox\Models\Zpanel\Store');
    }

    public function shipping () {
        return $this->belongsTo(TaxClass::class, 'shipping_tax');
    }
}
