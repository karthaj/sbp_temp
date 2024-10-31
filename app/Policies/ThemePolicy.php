<?php

namespace Shopbox\Policies;

use Shopbox\Models\Zpanel\User;
use Shopbox\Models\Front\StoreTheme;
use Illuminate\Auth\Access\HandlesAuthorization;

class ThemePolicy
{
    use HandlesAuthorization;

    public function touch(User $user, StoreTheme $store_theme)
    {
        return session('tenant') == $store_theme->store_id;
    }
    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }
}
