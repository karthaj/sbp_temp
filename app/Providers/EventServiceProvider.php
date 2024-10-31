<?php

namespace Shopbox\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Shopbox\Events\Auth\UserSignedUp' => [
            'Shopbox\Listeners\Auth\SendActivationEmail',
        ],
        'Shopbox\Events\Auth\UserRequestedActivationEmail' => [
            'Shopbox\Listeners\Auth\SendActivationEmail',
        ],
        'Shopbox\Events\Auth\CustomerSignedUp' => [
            'Shopbox\Listeners\Auth\SendVerificationEmail',
        ],
        'Shopbox\Events\Account\BillGenerated' => [
            'Shopbox\Listeners\Account\SendReminderNotification',
        ],
        'Shopbox\Events\Account\BillNotSettled' => [
            'Shopbox\Listeners\Account\SendReminderNotification',
        ],
        'Modules\Customer\Events\Auth\CustomerRegistered' => [
            'Modules\Customer\Listeners\Auth\SendVerificationEmail',
        ],
        'Shopbox\Events\Account\UserCreated' => [
            'Shopbox\Listeners\Account\SendActivationEmail',
        ],
        'Modules\Order\Events\Shipment\ReadyForShipment' => [
            'Modules\Order\Listeners\Shipment\SendShipmentNotification',
        ],
        'Modules\Order\Events\Export\StartExport' => [
            'Modules\Order\Listeners\Export\ProcessExport',
        ],

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
