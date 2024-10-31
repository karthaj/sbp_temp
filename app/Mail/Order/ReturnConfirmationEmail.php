<?php

namespace Shopbox\Mail\Order;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Order\Entities\OrderReturn;

class ReturnConfirmationEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $return;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(OrderReturn $return)
    {
        $this->return = $return;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
         return $this->view('emails.returns.confirmation')
                    ->subject($this->return->store->emails()->where('slug', 'return-confirmation')->first()->email_subject);
    }
}
