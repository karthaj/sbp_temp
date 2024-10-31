<?php

namespace Modules\Product\Entities;


use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = ['filename','size','maximum_downloads','accessible','active', 'created_at_tz', 'updated_at_tz'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
