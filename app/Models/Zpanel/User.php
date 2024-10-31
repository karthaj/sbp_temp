<?php

namespace Shopbox\Models\Zpanel;

use Illuminate\Notifications\Notifiable;
use Shopbox\Permissions\HasPermissionsTrait;
use Shopbox\Models\Zpanel\Traits\HasConfirmationTokens;
use Shopbox\Notifications\Merchant\ResetPasswordNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasPermissionsTrait, HasConfirmationTokens;

    protected $guard = 'admin';

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
       
    public function stores()
    {
        return $this->belongsToMany(Store::class, 'store_users')->withTimestamps();
    }

    public function storeLocations()
    {
        return $this->belongsToMany('Modules\Product\Entities\StoreLocation', 'store_location_users')->withTimestamps();
    }

    public function store()
    {
        return $this->belongsToMany(Store::class);
    }
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'phone', 'password', 'active', 'avatar', 'master'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function hasActivated()
    {
        return $this->active;
    }

    public function hasNotActivated()
    {
        return !$this->hasActivated();
    }

}
