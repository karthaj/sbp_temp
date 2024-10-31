<?php

namespace Shopbox\Mail\Order;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Modules\Order\Entities\Order;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $config;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
        $this->config = $order->store->configurations->pluck('value', 'name');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'Order Confirmation';

        if($this->order->store->emails()->where('slug', 'order-confirmation')->first()) {
            $subject = $this->order->store->emails()->where('slug', 'order-confirmation')->first()->email_subject.' - '.$this->order->store->store_name.','.$this->order->customer->firstname.','.$this->order->customer->lastname.','.$this->order->store_id.'-'.$this->order->order_id;
        } else {
            $subject = $this->order->store->store_name.','.$this->order->customer->firstname.','.$this->order->customer->lastname.','.$this->order->store_id.'-'.$this->order->order_id;
        }

        return $this->view('emails.orders.confirmation')
                    ->subject($subject);
    }

}
