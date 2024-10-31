<?php

namespace Modules\Customer\Entities;

use Illuminate\Database\Eloquent\Model;

class Guest extends Model
{
    protected $fillable = ['ip_address', 'browser', 'platform'];

    public function country()
    {
        return $this->belongsTo('Shopbox\Models\Zpanel\Country');
    }
}
