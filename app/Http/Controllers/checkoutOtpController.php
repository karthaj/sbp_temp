<?php

namespace Shopbox\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Product\Entities\CheckoutOtp; 

class checkoutOtpController extends Controller
{
  
    public function validateOtp($cartId, $inputOtp)
    {
        $otp = CheckoutOtp::where('cart_id', $cartId)->latest()->first();

        if (!$otp) {
            return response()->json(['message' => 'OTP not found.'], 404);
        }

        // Check if OTP is expired
        if ($otp->expires_at->isPast()) {
            return response()->json(['message' => 'OTP expired. Please request a new one.'], 403);
        }

        // Retrieve retry count and timestamp from session
        $retryCount =  session()->get("otp_retry_count_{$cartId}", 0);
        $timestamp =  session()->get("otp_timestamp_{$cartId}", now());

        // Check if 3 minutes have passed since the OTP was created
        if ($timestamp->diffInMinutes(now()) >= 3) {
            session()->forget("otp_retry_count_{$cartId}"); // Reset retry count
            session()->forget("otp_timestamp_{$cartId}");    // Reset timestamp
            return response()->json(['message' => 'OTP expired due to timeout. Please request a new one.'], 403);
        }

        // Validate OTP with retry limit
        if ($retryCount < 3) {
            if ($otp->otp_code === $inputOtp) {
                // OTP is valid
                session()->forget("otp_retry_count_{$cartId}"); // Reset retry count
                session()->forget("otp_timestamp_{$cartId}");    // Reset timestamp
                return response()->json(['message' => 'OTP verified successfully.']);
            } else {
                // Increment retry count on invalid attempt
                session()->put("otp_retry_count_{$cartId}", ++$retryCount);
                return response()->json(['message' => 'Invalid OTP. Attempts remaining: ' . (3 - $retryCount)], 422);
            }
        } else {
            // Max attempts reached within 3 minutes
            return response()->json(['message' => 'Maximum OTP attempts exceeded.'], 403);
        }
    }
    
    public function resendOtp($cartId)
    {
        // Retrieve the latest OTP entry for the cart
        $otp = CheckoutOtp::where('cart_id', $cartId)->latest()->first();

        if (!$otp) {
            return response()->json(['message' => 'OTP not found for this cart.'], 404);
        }

        // Check if OTP has expired or allow a forced resend
        $newOtpCode = Str::random(5); // Generate a new OTP code
        $newExpiresAt = now()->addMinutes(3); // Set a new expiration time of 3 minutes

        // Update the OTP in the database
        $otp->update([
            'otp_code' => $newOtpCode,
            'expires_at' => $newExpiresAt,
            'attempts' => 0, // Reset the attempt count
        ]);

        // Reset retry count and timestamp in session
        session()->put("otp_retry_count_{$cartId}", 0);
        session()->put("otp_timestamp_{$cartId}", now());

        // Send OTP to the customer again (e.g., by email)
        Mail::to($otp->cart->customer->email)->queue(new OtpEmail($otp->cart, $newOtpCode));

        return response()->json(['message' => 'OTP has been resent successfully.']);
    }
 
}
