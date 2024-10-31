<?php

namespace Shopbox\Mail\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $store_url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token, $store_url)
    {
        $this->token = $token;
        $this->store_url = $store_url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Please verify your account')->markdown('emails.auth.verification');
    }
}
