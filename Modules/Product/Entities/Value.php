<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;

class Value extends Model
{
    protected $table = "feature_values";
    protected $fillable = ['value', 'custom'];

    public function feature()
    {
        return $this->belongsTo(Feature::class);
    }
}
