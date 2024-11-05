<?php

namespace Modules\Product\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Product\Models\Product;
use Modules\User\Models\User;

class ProductPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
        return $user->hasPermissionTo('viewAny products');
    }

    public function view(User $user, Product $product)
    {
        return $user->hasPermissionTo('view products');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('create products');
    }

    public function update(User $user, Product $product)
    {
        return $user->hasPermissionTo('update products');
    }

    public function delete(User $user, Product $product)
    {
        return $user->hasPermissionTo('delete products');
    }
}
