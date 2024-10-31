<?php

namespace Shopbox\Http\Controllers;

use Illuminate\Http\Request;

class checkoutOtpController extends Controller
{

    public function verifyOtp(Cart $cart,Request $request)
    { 
        // Validate incoming request
        $request->validate([  
            'otp_code' => 'required|string',
        ]);
 
        $otpCode = $request->input('otp_code');

        // Fetch the OTP record associated with the cart ID
        $otpRecord = Otp::where('cart_id', $cart->id)->first();

        if (!$otpRecord) {
            return response()->json(['message' => 'OTP not found.'], 404);
        }

        // Check if the OTP is expired (you can set your own expiration logic)
        if ($otpRecord->isExpired()) {
            return response()->json(['message' => 'OTP has expired.'], 400);
        }

        // Check if the OTP is valid
        if ($otpRecord->otp_code === $otpCode) {
            // Mark OTP as used or delete it 

            return response()->json(['message' => 'OTP verified successfully.']);
        } else {
            // Increment the attempt counter
            $otpRecord->increment('attempts');

            // Check if attempts exceed 3
            if ($otpRecord->attempts >= 3) {
                // Optionally delete the OTP record after three attempts
                $otpRecord->delete();
                return response()->json(['message' => 'Maximum attempts reached. OTP is now invalid.'], 403);
            }

            return response()->json(['message' => 'Invalid OTP.'], 400);
        }
    } 
 
}
