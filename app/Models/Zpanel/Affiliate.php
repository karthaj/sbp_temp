<?php

namespace Shopbox\Models\Zpanel;

use Illuminate\Database\Eloquent\Model;

class Affiliate extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'ref_code',
        'commission',
    ];
    
    public function referrals()
    {
        return $this->hasMany(Referral::class);
    }
}
