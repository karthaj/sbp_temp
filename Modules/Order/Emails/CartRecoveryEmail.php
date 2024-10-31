<?php

namespace Modules\Order\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Modules\Product\Entities\Cart;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Shopbox\Transformers\Cart\CartTransformer;

class CartRecoveryEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $cart;
    public $store;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Cart $cart)
    {
        $this->cart = fractal()->item($cart)->transformWith(new CartTransformer)->toArray()['data'];
        $this->store = $cart->store;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('order::emails.carts.abandoned')
                    ->subject($this->store->emails()->where('slug', 'abandoned-cart-notification')->first()->email_subject);
    }
}
