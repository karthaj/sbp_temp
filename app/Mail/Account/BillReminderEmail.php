<?php

namespace Shopbox\Mail\Account;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Shopbox\Models\Zpanel\Billing;
use Illuminate\Queue\SerializesModels;
use Shopbox\Models\Zpanel\BillingReminder;
use Illuminate\Contracts\Queue\ShouldQueue;

class BillReminderEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $bill;
    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Billing $bill)
    {
        $this->bill = $bill;
        $user = $bill->store->users()->where('master', 1)->first();

        if($user) {
            $this->user = $user->first_name.' '.$user->last_name;
        }

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $subject = 'ShopBox - '.title_case($this->bill->store->store_name).' - Bill';
        $ref = 'Initial Reminder';
        if($this->bill->reminders->count()) {
            if($this->bill->reminders->count() == 1) {
                $ref = 'First Reminder';
                $subject.=' - First Reminder!';
            } else if($this->bill->reminders->count() == 2) {
                $ref = 'Second Reminder';
                $subject.=' - Second Reminder!';
            } else if($this->bill->reminders->count() == 3) {
                $ref = 'Third Reminder';
                $subject.=' - Third Reminder!';
            } else if($this->bill->reminders->count() == 4) {
                $ref = 'Final Reminder';
                $subject.=' - Final Reminder!';
            }
        }

        $reminder = new BillingReminder;
        $reminder->billing()->associate($this->bill);
        $reminder->ref = $ref;
        $reminder->created_at = $reminder->freshTimestamp();
        $reminder->save();

        return $this->subject($subject)->view('emails.store.bill');
    }
}
