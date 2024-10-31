<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable = ['name', 'type', 'swatch_type', 'color', 'pattern', 'sort_order', 'created_at_tz', 'updated_at_tz'];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }
}
