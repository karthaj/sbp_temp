<?php

namespace Modules\Customer\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerificationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $token;
    public $customer;
    public $store;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token, $customer, $store)
    {
        $this->token = $token;
        $this->customer = $customer;
        $this->store = $store;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        //return $this->view('view.name');
        return $this->subject('Account verification')->markdown('customer::emails.auth.verification');
    }
}
