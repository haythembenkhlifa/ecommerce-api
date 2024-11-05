<?php

namespace Modules\User\Actions\Auth;

use Illuminate\Support\Facades\Auth;
use Modules\User\Http\Requests\LoginRequest;
use Modules\User\Models\User;

class Login
{
    public function __invoke(LoginRequest $loginRequest)
    {
        if (!Auth::guard('web')->attempt($loginRequest->only('email', 'password')))
            return response()->json(['error' => 'Invalid credentials'], 401);

        $user = Auth::guard('web')->user();

        // Delete old tokens
        $user->tokens()->delete();

        $tokenObject = $user->createToken('Personal Access Token');

        return response()->json([
            'access_token' => $tokenObject->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => now()->addMinutes(config('passport.personal_access_token_expires_in', 60 * 60 * 15))->toDateTimeString(),
            'refresh_token' => $user->createToken('Refresh Token')->accessToken
        ]);
    }
}
