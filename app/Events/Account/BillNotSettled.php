<?php

namespace Shopbox\Events\Account;

use Shopbox\Models\Zpanel\Billing;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class BillNotSettled
{
    use Dispatchable, SerializesModels;

    public $bill;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Billing $bill)
    {
        $this->bill = $bill;
    }

}
