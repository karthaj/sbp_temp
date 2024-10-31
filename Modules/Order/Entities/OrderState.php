<?php

namespace Modules\Order\Entities;

use Illuminate\Database\Eloquent\Model;

class OrderState extends Model
{
    protected $fillable = ['name', 'color', 'invoice', 'send_invoice'];

    
}
