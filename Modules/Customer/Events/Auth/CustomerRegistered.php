<?php

namespace Modules\Customer\Events\Auth;

use Modules\Customer\Entities\Customer;
use Shopbox\Tenant\Manager;
use Illuminate\Queue\SerializesModels;

class CustomerRegistered
{
    use SerializesModels;

    public $customer;
    public $store;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
        $this->store = request()->tenant();
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
