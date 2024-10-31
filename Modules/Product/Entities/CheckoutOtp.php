<?php

namespace Modules\Product\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class CheckoutOtp extends Model
{
    protected $fillable = ['cart_id', 'otp_code', 'expires_at', 'attempts'];

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
    public static function createOrUpdateOtp($cartId)
    {
        $otpCode = self::generateOtp();
        $expiresAt = Carbon::now()->addMinutes(5); // Set expiration to 5 minutes

        return self::updateOrCreate(
            ['cart_id' => $cartId],
            [
                'otp_code' => $otpCode,
                'expires_at' => $expiresAt,
                'attempts' => 0,
            ]
        );
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
     * Reset the OTP attempts counter.
     *
     * @return void
     */
    public function resetAttempts()
    {
        $this->update(['attempts' => 0]);
    }

    /**
     * Check if the maximum number of attempts has been reached.
     *
     * @return bool
     */
    public function hasReachedMaxAttempts($maxAttempts = 3)
    {
        return $this->attempts >= $maxAttempts;
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
