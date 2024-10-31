<?php

namespace Shopbox\Models;

use Illuminate\Database\Eloquent\Model;

class Wishlist extends Model
{
    public function customer() 
    {
        return $this->belongsTo('Modules\Customer\Entities\Customer');
    }

    public function store() 
    {
        return $this->belongsTo('Shopbox\Models\Zpanel\Store');
    }

    public function product() 
    {
        return $this->belongsTo('Modules\Product\Entities\Product');
    }

}
