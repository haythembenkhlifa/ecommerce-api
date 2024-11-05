<?php

namespace Modules\User\Policies;

use Exception;
use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\User\Models\User;

class UserPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('viewAny users');
    }

    public function view(User $user)
    {
        return $user->hasPermissionTo('view users');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('create users');
    }

    public function update(User $user, User $selectedUser)
    {
        new Exception('test');
        $canUpdateAnyUser = true;
        if (!$user->hasRole(['admin', 'user-manager'])) {
            $canUpdateAnyUser = $user->id === $selectedUser->id;
        }

        return $user->hasPermissionTo('update users') && $canUpdateAnyUser;
    }

    public function delete(User $user)
    {
        return $user->hasPermissionTo('delete users');
    }
}
