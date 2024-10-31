<?php

namespace Shopbox\Events\Auth;

use Modules\Customer\Entities\Customer;
use Shopbox\Models\Zpanel\Store;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class CustomerSignedUp
{
    use Dispatchable, SerializesModels;

    public $customer;
    public $store_url;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Customer $customer, $store_url)
    {
        $this->customer = $customer;
        $this->store_url = $store_url;
    }

}
