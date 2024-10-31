<?php

namespace Modules\Order\Events\Shipment;

use Modules\Order\Entities\Order;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class ReadyForShipment
{
    use Dispatchable, SerializesModels;

    public $order;
    public $shipper;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Order $order, $shipper)
    {
        $this->order = $order;    
        $this->shipper = $shipper;
    }

}
