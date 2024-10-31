<?php

namespace Shopbox\Models\Zpanel;

use Illuminate\Database\Eloquent\Model;

class ConfirmationToken extends Model
{
    public $timestamps = false;

    protected $dates = ['expires_at'];

    protected $fillable = ['token', 'expires_at'];

    public static function boot()
    {
    	static::creating(function ($token) {
    		if($token->user->ConfirmationToken) {
    			$token->user->ConfirmationToken->delete();
    		}
    	});
    }

    public function getRouteKeyName()
    {
    	return 'token';
    }

    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function hasExpired()
    {
    	return $this->freshTimestamp()->gt($this->expires_at);
    }
}
