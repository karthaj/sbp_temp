<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Shopbox\Tenant\Traits\ForTenants;
use Shopbox\Models\Zpanel\Store;
use Modules\Product\Entities\StockManager;
use Modules\Product\Entities\StoreStock;
use Modules\Product\Entities\StoreLocation;

class Stock extends Model
{

    use ForTenants;

    protected $fillable = ['physical_quantity','available_quantity','out_of_stock'];

    public function store ()
    {
        return $this->belongsTo('Shopbox\Models\Zpanel\Store');
    }

    public function product() 
    {
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function productAttribute() 
    {
        return $this->belongsTo(ProductAttribute::class);
    }

    public function storeStocks() 
    {
        return $this->hasMany(StoreStock::class);
    }

}
