<?php

namespace Shopbox\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    public $customer;
    public $store;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token, $store)
    {
        $this->token = $token;
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
        return (new MailMessage)
                    ->greeting('Hello there!')
                    ->line('This password reset link has been sent to you by '.$this->store->store_name.'. Please click on the button below to reset your password.')
                    ->action('Reset Password', url(getStoreUrl($this->store).'/password/reset/'.$this->token))
                    ->line('This is a special request as we are migrating to a newer system. Your details have been moved but your passwords cannot be. This is a one time reset for migration purposes.')
                    ->line('Please bare with us and we thank you for your patience.')
                    ->line('PS. Please ignore this email, If you have received a similar email and have completed the password reset.');
    }

}
