<?php

namespace Shopbox\Policies;

use Shopbox\Models\Zpanel\User;
use Modules\Product\Entities\Product;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
{
    use HandlesAuthorization;

    public function touch(User $user, Product $product)
    {
        return session('tenant') == $product->store_id;
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
