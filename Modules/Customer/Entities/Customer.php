<?php

namespace Modules\Customer\Entities;

use Shopbox\Models\Zpanel\Traits\HasConfirmationTokens;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Shopbox\Notifications\Customer\ResetPasswordNotification;

class Customer extends Authenticatable
{
    use Notifiable, SoftDeletes, HasConfirmationTokens;
    
    protected $fillable = [
    	'firstname', 
    	'lastname', 
        'password',
    	'email', 
        'email_guest',
    	'phone', 
    	'company', 
        'avatar',
    	'note', 
    	'newsletter', 
    	'is_guest', 
    	'active', 
    	'ip_address', 
    	'platform', 
    	'browser'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    public function getCustomerEmailAttribute()
    {
        return $this->email ?: $this->email_guest;
    }

    public function getFullNameAttribute()
    {
        return $this->firstname.' '.$this->lastname;
    }

    public function groups() 
    {
        return $this->belongsToMany(Group::class, 'customer_groups')->withTimestamps();
    }
    
    public function stores ()
    {
        return $this->belongsToMany('Shopbox\Models\Zpanel\Store', 'store_customers')->withTimestamps();
    }

    public function addresses ()
    {
        return $this->hasMany(Address::class);
    }

    public function hasActivated()
    {
        return $this->active;
    }

    public function hasNotActivated()
    {
        return !$this->hasActivated();
    }

    public function carts ()
    {
        return $this->hasMany('Modules\Product\Entities\Cart');
    }

    public function orders ()
    {
        return $this->hasMany('Modules\Order\Entities\Order')->orderBy('id', 'desc');
    }

    public function returns ()
    {
        return $this->hasMany('Modules\Order\Entities\OrderReturn')->orderBy('id', 'desc');
    }

    public function wishlists ()
    {
        return $this->hasMany('Shopbox\Models\Wishlist')->orderBy('created_at', 'desc');
    }

    public function credits ()
    {
        return $this->hasMany('Shopbox\Models\Zpanel\StoreCredit');
    }
}
