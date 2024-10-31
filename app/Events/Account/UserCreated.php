<?php

namespace Shopbox\Events\Account;

use Shopbox\Models\Zpanel\User;
use Shopbox\Tenant\Manager;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class UserCreated
{
    use Dispatchable, SerializesModels;

    public $user;
    public $store;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        $this->store = app(Manager::class)->getTenant();
    }

}
