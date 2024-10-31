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
     

    public function resendOtp(Cart $cart, Request $request)
    {
        // Find the corresponding OTP record
        $otpRecord = CheckoutOtp::where('cart_id', $checkout_id)->first();

        // Check if the record exists
        if (!$otpRecord) {
            return response()->json(['success' => false, 'message' => 'OTP record not found.'], 404);
        }

        // Generate a new OTP
        $newOtp = CheckoutOtp::generateOtp();
        $otpRecord->otp_code = $newOtp;
        $otpRecord->expires_at = now()->addMinutes(3); // Reset expiration
        $otpRecord->attempts = 0; // Reset attempts
        $otpRecord->save(); // Save the new OTP

        // Send the OTP to the user's email
        // You can replace the following code with your own email logic
        Mail::to($request->user()->email)->send(new \App\Mail\OtpMail($newOtp)); // Create an OtpMail class to handle email

        return response()->json(['success' => true, 'message' => 'OTP has been resent successfully.']);
    }
 
}
