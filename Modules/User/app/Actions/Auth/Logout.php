<?php

namespace Modules\User\Actions\Auth;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Modules\User\Http\Requests\LoginRequest;
use Modules\User\Http\Requests\UserRequest;
use Modules\User\Models\User;

class Logout
{
    public function __invoke(User $user)
    {
        $user->tokens()->delete();
        return response()->json(['message' => 'Successfully logged out']);
    }
}
