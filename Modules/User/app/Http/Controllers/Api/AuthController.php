<?php

namespace Modules\User\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\User\Actions\Auth\Login;
use Modules\User\Actions\Auth\Logout;
use Modules\User\Actions\Auth\RefreshToken;
use Modules\User\Http\Requests\LoginRequest;
use Modules\User\Http\Requests\RefreshTokenRequest;
use Modules\User\Transformers\UserResource;

class AuthController extends Controller
{

    /**
     * Login user.
     */
    public function login(LoginRequest $loginRequest)
    {
        return (new Login)($loginRequest);
    }

    /**
     * Logout user.
     */
    public function logout()
    {
        return (new Logout)(Auth::user());
    }

    /**
     * Refresh user token.
     */
    public function refreshToken(RefreshTokenRequest $refreshTokenRequest)
    {
        return (new RefreshToken)($refreshTokenRequest);
    }

    /**
     * Get current user.
     */
    public function me()
    {
        return new UserResource(Auth::user());
    }
}
