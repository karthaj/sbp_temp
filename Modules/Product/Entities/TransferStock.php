<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;

class TransferStock extends Model
{
    protected $fillable = ['quantity', 'discount', 'price'];

    public function transfer () {
        return $this->belongsTo(Transfer::class);
    }

    public function stock () {
        return $this->belongsTo(Stock::class);
    }
}
