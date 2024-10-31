<?php

namespace Modules\Order\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Order\Entities\Order;

class OrderStatusEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $order;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = $this->order->store->emails()->where('slug', 'order-status')->first()->email_subject;

        if($this->order->state->slug == 'refund') {
            $subject = $this->order->store->store_name.', '.$this->order->store_id.'-'.$this->order->order_id.', Order Refunded!';
        }

        return $this->view('order::emails.orders.status')
                    ->subject($subject);
    }
}
