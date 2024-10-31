<?php

namespace Modules\Product\Entities;

use Shopbox\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\StockTransferReason;

class StockTransfer extends Model
{
    use ForTenants;
    
    protected $fillable = ['employee','quantity','sign'];

    protected $dates = ['created_at_tz'];

    public function store ()
    {
        return $this->belongsTo('Shopbox\Models\Zpanel\Store');
    }

    public function user ()
    {
        return $this->belongsTo('Shopbox\Models\Zpanel\User');
    }

    public function stock ()
    {
        return $this->belongsTo(Stock::class);
    }

    public function reason ()
    {
        return $this->belongsTo(StockTransferReason::class, 'stock_transfer_reason_id');
    }

    public function store_location ()
    {
        return $this->belongsTo(StoreLocation::class, 'entity');
    }

    public function order ()
    {
        return $this->belongsTo('Modules\Order\Entities\Order');
    }

}
