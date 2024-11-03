<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Modules\Product\Entities\Cart;
use Carbon\Carbon;


class CheckoutOtp extends Model
{
    protected $fillable = ['cart_id', 'otp_code', 'expires_at', 'attempts', 'status'];
    protected $table = 'checkout_otp';


    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Generate a new OTP code and set its expiration.
     *
     * @return string
     */
    public static function generateOtp()
    {
        return str_pad(rand(0, 99999), 5, '0', STR_PAD_LEFT); // Generate a 6-digit OTP
    }

    /**
     * Create or update the OTP record for the given cart ID.
     *
     * @param int $cartId
     * @return self
     */
    public static function createNew($cartId)
    {
      
        $otpCode = self::generateOtp();
        $expiresAt = Carbon::now()->addMinutes(5); // Set expiration to 5 minutes
 

        // Create a new OTP record
        return self::create([
            'cart_id' => $cartId,
            'otp_code' => $otpCode,
            'status' => 0, // 0 : new | 1 : used | 2 : authenticated | 3 : expired
            'expires_at' => $expiresAt,
            'attempts' => 0,
        ]);
    }

    /**
     * Check if the OTP has expired.
     *
     * @return bool
     */
    public function hasExpired()
    {
        return Carbon::now()->greaterThan($this->expires_at);
    }

    /**
     * Increment the attempts counter for this OTP.
     *
     * @return void
     */
    public function incrementAttempts()
    {
        $this->increment('attempts');
    } 
  
    /**
     * Check if the maximum number of attempts has been reached.
     *
     * @return bool
     */
    public function hasReachedMaxAttempts($maxAttempts )
    { 
        return $this->attempts  > $maxAttempts ;
    }

    /**
     * Verify the OTP against the provided code.
     *
     * @param string $otpCode
     * @return bool
     */
    public function verifyOtp($otpCode)
    {
        return !$this->hasExpired() && $this->otp_code === $otpCode;
    }
}
