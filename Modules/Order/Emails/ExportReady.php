<?php

namespace Modules\Order\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ExportReady extends Mailable
{
    use Queueable, SerializesModels;

    public $store;
    public $url;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($store)
    {
        $this->store = $store;
        $this->url = 'https://'.$store->domain.'.'.config('domain.app_domain').'/merchant/orders/11000/download/export';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
         return $this->subject($this->store->store_name.' Orders Export Ready')
                        ->view('order::emails.orders.export_ready');
    }
}
