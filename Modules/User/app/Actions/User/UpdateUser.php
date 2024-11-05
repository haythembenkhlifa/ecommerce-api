<?php

namespace Modules\User\Actions\User;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Modules\User\Http\Requests\UserRequest;
use Modules\User\Models\User;

class UpdateUser
{
    public function __invoke(UserRequest $userRequest, User $user): User
    {

        $user->name = $userRequest->input('name', $user->name);
        $user->email = $userRequest->input('email', $user->email);
        $user->phone_number = $userRequest->input('phone_number', $user->phone_number);
        $user->password = Hash::make($userRequest->input('password')) ?? $user->password;
        $user->save();

        Cache::forget("user.{$user->id}");

        return $user;
    }
}
