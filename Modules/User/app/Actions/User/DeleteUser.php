<?php

namespace Modules\User\Actions\User;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Modules\User\Http\Requests\UserRequest;
use Modules\User\Models\User;

class DeleteUser
{
    public function __invoke(User $user): void
    {
        $user->delete();
        Cache::forget("user.{$user->id}");
    }
}
