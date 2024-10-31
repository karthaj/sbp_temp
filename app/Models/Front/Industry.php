<?php

namespace Shopbox\Models\Front;

use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{
    protected $fillable = ['name', 'slug'];

    public function themes()
    {
    	return $this->belongsToMany(Theme::class);
    }
}
