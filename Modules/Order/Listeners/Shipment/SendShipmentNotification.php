<?php

namespace Modules\Order\Listeners\Shipment;

use Illuminate\Support\Facades\Mail;
use Modules\Order\Emails\ReadyForShipment;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendShipmentNotification implements ShouldQueue
{

    /**
     * Handle the event.
     *
     * @param  UserCreated  $event
     * @return void
     */
    public function handle($event)
    {
        Mail::to($event->shipper)->queue(new ReadyForShipment($event->order));
    }
}
