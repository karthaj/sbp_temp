<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;

class DeliveryRate extends Model
{
    protected $fillable = ['delimiter1', 'delimiter2', 'price', 'created_at_tz', 'updated_at_tz'];
}
