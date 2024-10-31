<?php

namespace Shopbox\Listeners\Account;

use Shopbox\Mail\Account\ActivationEmail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendActivationEmail implements ShouldQueue
{

    /**
     * Handle the event.
     *
     * @param  UserCreated  $event
     * @return void
     */
    public function handle($event)
    {
        Mail::to($event->user)->queue(new ActivationEmail($event->user->generateConfirmationToken(), $event->user, $event->store));
    }
}
