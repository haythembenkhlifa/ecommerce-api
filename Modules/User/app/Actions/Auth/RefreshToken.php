<?php

namespace Modules\User\Actions\Auth;

use Illuminate\Support\Facades\Auth;
use Laravel\Passport\RefreshToken as PassportRefreshToken;
use Modules\User\Http\Requests\RefreshTokenRequest;

class RefreshToken
{
    public function __invoke(RefreshTokenRequest $refreshTokenRequest)
    {

        $refreshToken = $refreshTokenRequest->input('refresh_token');

        // Here you should validate the refresh token against your database
        $this->validateRefreshToken($refreshToken);


        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        // Delete old tokens
        $user->tokens()->delete();

        // Generate new access token
        $tokenObject = $user->createToken('Personal Access Token');

        return response()->json([
            'access_token' => $tokenObject->accessToken,
            'token_type' => 'Bearer',
            'expires_at' => now()->addMinutes(config('passport.personal_access_token_expires_in', 60 * 60 * 15))->toDateTimeString(),
            'refresh_token' => $user->createToken('Refresh Token')->accessToken

        ]);
    }


    private function validateRefreshToken($refreshToken)
    {
        // Assuming the refresh token is stored in your tokens table
        $token = PassportRefreshToken::where('id', $refreshToken)->first();

        if (!$token || $token->revoked) return null;

        return $token;
    }

    private function generateNewRefreshTokenForUser($user)
    {
        // Implement your logic to generate a new refresh token
        // You might store it in the database with a reference to the user
    }
}
