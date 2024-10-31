<?php

namespace Shopbox\Notifications\Merchant;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class TrialExpired extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $store;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($store)
    {
        $this->store = $store;
    }


    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $store = $this->store->store_name;
        $url = url($this->store->domain.'.'.config('domain.app_domain').'/merchant/login');
        return (new MailMessage)
                    ->subject('ShopBox - '.$this->store->store_name.' - Trial Expired!')
                    ->view(
                        'emails.store.trial_expired', ['store' => $store, 'url' => $url]
                    );
    }
}
