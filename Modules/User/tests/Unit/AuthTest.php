<?php

namespace Modules\User\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Modules\User\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase
{

    /**
     * User can login and receive token.
     */
    public function test_user_can_login_and_receive_token()
    {
        Passport::$hashesClientSecrets = false;

        $this->artisan(
            'passport:client',
            ['--name' => config('app.name'), '--personal' => null]
        )->assertSuccessful();

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'customer@eshop.com',
            'password' => '010203',
        ]);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'access_token',
                'expires_at',
                'token_type',
            ]);
    }

    /** 
     * User can access protected route with valid token.
     */
    public function test_user_can_access_protected_route_with_valid_token()
    {
        Passport::$hashesClientSecrets = false;

        $this->artisan(
            'passport:client',
            ['--name' => config('app.name'), '--personal' => null]
        )->assertSuccessful();

        $user = User::first();
        Passport::actingAs($user);
        $response = $this->getJson('/api/v1/auth/me');
        $response->assertStatus(200);
    }

    /** 
     * User cannot access protected route without token.
     */
    public function test_user_cannot_access_protected_route_without_token()
    {
        $response = $this->getJson('/api/v1/auth/me');
        $response->assertStatus(401);
    }

    /**
     * User cannot access protected route with invalid token.
     */
    public function test_user_cannot_access_protected_route_with_invalid_token()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer invalid-token',
        ])->getJson('/api/v1/auth/me');
        $response->assertStatus(401);
    }
}
