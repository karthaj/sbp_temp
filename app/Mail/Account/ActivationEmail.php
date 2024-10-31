<?php

namespace Shopbox\Mail\Account;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ActivationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $store;
    public $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($token, $user, $store)
    {
        $this->user = $user;
        $this->store = $store;
        $this->url = '//'.$store->domain.'.'.config('domain.app_domain').'/activation/active/'.$token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('ShopBox – Staff Access to '.$this->store->store_name.'!')->markdown('emails.account.activation');
    }
}
