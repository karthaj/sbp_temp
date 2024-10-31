<?php

namespace Shopbox\Models\Zpanel;

use Illuminate\Database\Eloquent\Model;

class VerificationToken extends Model
{
    public $timestamps = false;

    protected $dates = ['expires_at'];

    protected $fillable = ['token', 'expires_at'];

    public static function boot()
    {
    	static::creating(function ($token) {
    		if($token->customer->verificationToken) {
    			$token->customer->verificationToken->delete();
    		}
    	});
    }

    public function getRouteKeyName()
    {
    	return 'token';
    }

    public function customer()
    {
    	return $this->belongsTo('Modules\Customer\Entities\Customer');
    }

    public function hasExpired()
    {
    	return $this->freshTimestamp()->gt($this->expires_at);
    }
}
