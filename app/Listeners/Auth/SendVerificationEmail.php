<?php

namespace Shopbox\Listeners\Auth;

use Shopbox\Mail\Auth\VerificationEmail;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Mail;

class SendVerificationEmail implements ShouldQueue
{
    /**
     * Handle the event.
     *
     * @param  UserSignedUp  $event
     * @return void
     */
    public function handle($event)
    {
       Mail::to($event->customer)->queue(new VerificationEmail($event->customer->generateVerificationToken(), $event->store_url));
    }
}
