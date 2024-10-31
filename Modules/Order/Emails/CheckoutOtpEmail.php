<?php

namespace App\Mail; // Change this to the appropriate namespace if in a module

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $otpCode;
    public $customerEmail;

    /**
     * Create a new message instance.
     *
     * @param string $otpCode
     * @param string $customerEmail
     */
    public function __construct($otpCode, $customerEmail, $storeName)
    {
        $this->otpCode = $otpCode;
        $this->customerEmail = $customerEmail;
        $this->storeName = $storeName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.otp') // Create this view in resources/views/emails/otp.blade.php
                    ->subject('Your OTP Code')
                    ->with([
                        'otpCode' => $this->otpCode,
                    ]);
    }
}
