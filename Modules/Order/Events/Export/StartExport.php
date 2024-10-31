<?php

namespace Modules\Order\Events\Export;

use Shopbox\Models\Zpanel\Store;
use Modules\Order\Entities\Order;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class StartExport
{
    use Dispatchable, SerializesModels;

    public $store;
    public $orders;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Store $store, $orders)
    {
        $this->store = $store;
        $this->orders = $orders;
    }

}
