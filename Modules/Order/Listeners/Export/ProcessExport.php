<?php

namespace Modules\Order\Listeners\Export;

use Shopbox\Traits\OrderExport;
use Illuminate\Support\Facades\Mail;
use Modules\Order\Emails\ExportReady;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProcessExport implements ShouldQueue
{
    use OrderExport;
    /**
     * Handle the event.
     *
     * @param  UserCreated  $event
     * @return void
     */
    public function handle($event)
    {
        $this->startExport($event->orders, $event->store->id);

        Mail::to($event->store->store_email)->cc('demo@aidantz.com')->queue(new ExportReady($event->store));
    }
}
