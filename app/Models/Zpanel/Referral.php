<?php

namespace Shopbox\Models\Zpanel;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    public function affiliate()
    {
        return $this->belongsTo(Affiliate::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
