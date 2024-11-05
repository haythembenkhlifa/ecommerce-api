<?php

namespace Modules\User\Actions\User;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Modules\User\Http\Requests\UserRequest;
use Modules\User\Models\User;

class CreateUser
{
    public function __invoke(UserRequest $userRequest): User
    {
        $user = new User;
        $user->name = $userRequest->input('name');
        $user->email = $userRequest->input('email');
        $user->phone_number = $userRequest->input('phone_number');
        $user->password = Hash::make($userRequest->input('password'));
        $user->save();

        return $user;
    }
}
