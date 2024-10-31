<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Shopbox\Transformers\Cart\ProductTransformer;

class Cart extends Model
{
    use SoftDeletes;
    
    protected $fillable = ['message'];
    protected $dates = ['reserved_at','reservation_ends_at', 'created_at_tz'];

    public function getRouteKeyName()
    {
        return 'reference';
    }

    public function requiredShipping()
    {
        foreach ($this->items as $item) {
            if($item->product->type != 'virtual') {
                return true;
            }
        }

        return false;
    }

    public function checkInventory()
    {
        $check = 1;

        if($this->stock_reserved) {
            return $check;
        }

        $items = fractal()->collection($this->items)->transformWith(new ProductTransformer)->toArray()['data'];

        foreach ($items as $item) {
            if((!$item['preorder'] && !$item['backorder']) && $item['quantity'] > $item['stock_count']) {
                $check = 0;
                break;
            }
        }

        return $check;
    }

    public function store()
    {
        return $this->belongsTo('Shopbox\Models\Zpanel\Store');
    }

    public function guest()
    {
        return $this->belongsTo('Modules\Customer\Entities\Guest');
    }

    public function customer()
    {
        return $this->belongsTo('Modules\Customer\Entities\Customer');
    }

    public function carrier()
    {
        return $this->hasOne(CartCarrier::class);
    }

    public function delivery_address()
    {
        return $this->belongsTo('Modules\Customer\Entities\Address', 'delivery_address_id');
    }

    public function invoice_address()
    {
        return $this->belongsTo('Modules\Customer\Entities\Address', 'invoice_address_id');
    }

    public function currency()
    {
        return $this->belongsTo('Shopbox\Models\Zpanel\Currency');
    }

    public function items()
    {
        return $this->hasMany(CartProduct::class)->orderBY('created_at', 'desc');
    }
    
    public function discounts()
    {
        return $this->hasMany(CartDiscount::class);
    }

    public function order()
    {
        return $this->hasOne('Modules\Order\Entities\Order')->where('status', 0);
    }
    
}
