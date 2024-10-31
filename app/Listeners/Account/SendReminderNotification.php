<?php

namespace Shopbox\Listeners\Account;

use Shopbox\Mail\Account\BillReminderEmail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendReminderNotification implements ShouldQueue
{

    /**
     * Handle the event.
     *
     * @param  UserCreated  $event
     * @return void
     */
    public function handle($event)
    {
        Mail::to($event->bill->store->store_email)
            ->bcc('demo@aidantz.com')
            ->queue(new BillReminderEmail($event->bill));
    }
}
