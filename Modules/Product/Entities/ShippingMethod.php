<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    protected $fillable = ['name', 'status', 'description'];
}
