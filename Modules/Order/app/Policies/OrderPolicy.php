<?php

namespace Modules\Order\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Order\Models\Order;
use Modules\User\Models\User;

class OrderPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('viewAny orders');
    }

    public function view(User $user, Order $order)
    {
        return $user->hasPermissionTo('view orders');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('create orders');
    }

    public function update(User $user, Order $order)
    {
        return $user->hasPermissionTo('update orders');
    }

    public function delete(User $user, Order $order)
    {
        return $user->hasPermissionTo('delete orders');
    }
}
