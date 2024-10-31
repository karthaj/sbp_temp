<?php

namespace Modules\Customer\Listeners\Auth;

use Modules\Customer\Emails\VerificationEmail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendVerificationEmail
{

    /**
     * Handle the event.
     *
     * @param \Modules\Customer\\Auth\Events\CustomerRegistered $event
     * @return void
     */
    public function handle($event)
    {
        Mail::to($event->customer)->send(new VerificationEmail($event->customer->generateVerificationToken(), $event->customer, $event->store));
    }
}
