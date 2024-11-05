<?php

namespace Modules\User\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Modules\User\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    /** 
     * Admin and user manager only can create users.
     */
    public function test_admin_and_user_manager_only_can_create_a_user()
    {
        $adminUser = User::where('email', 'admin@eshop.com')->first();
        $managerUser = User::where('email', 'user-manager@eshop.com')->first();
        $orderUser = User::where('email', 'order-manager@eshop.com')->first(); // this user can not create user.

        $payload = [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password@123',
            'phone_number' => '99999999'
        ];

        $payload2 = array_merge($payload, ['email' => 'johndoe2@example.com', 'phone_number' => '99999990']);

        $payload3 = array_merge($payload, ['email' => 'johndoe3@example.com', 'phone_number' => '99999991']);

        Passport::actingAs($adminUser);
        $this->postJson('/api/v1/users', $payload)->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'email' => 'johndoe@example.com'
        ]);

        Passport::actingAs($managerUser);
        $this->postJson('/api/v1/users', $payload2)->assertStatus(201);
        $this->assertDatabaseHas('users', [
            'email' => 'johndoe2@example.com'
        ]);

        Passport::actingAs($orderUser);
        $this->postJson('/api/v1/users', $payload3)->assertStatus(403);
    }

    /**
     * Admin and user manager only can updates users.
     */
    public function test_admin_and_user_manger_only_can_update_a_user()
    {
        $adminUser = User::where('email', 'admin@eshop.com')->first();
        $managerUser = User::where('email', 'user-manager@eshop.com')->first();
        $orderUser = User::where('email', 'order-manager@eshop.com')->first(); // this user can not create user.

        $payload = [
            'email' => 'johndoe1@example.com',
        ];
        $payload2 = [
            'email' => 'johndoe2@example.com',
        ];
        $payload3 = [
            'email' => 'johndoe3@example.com',
        ];

        Passport::actingAs($adminUser);
        $this->putJson('/api/v1/users/5', $payload)->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'email' => 'johndoe1@example.com'
        ]);

        Passport::actingAs($managerUser);
        $this->putJson('/api/v1/users/5', $payload2)->assertStatus(200);
        $this->assertDatabaseHas('users', [
            'email' => 'johndoe2@example.com'
        ]);

        Passport::actingAs($orderUser);
        $this->putJson('/api/v1/users/5', $payload3)->assertStatus(403);
    }

    /**
     * Admin and user manager only can delete users. 
     */
    public function test_admin_and_user_manger_can_only_delete_a_user()
    {
        $adminUser = User::where('email', 'admin@eshop.com')->first();
        $userManagerUser = User::where('email', 'user-manager@eshop.com')->first();
        $orderUser = User::where('email', 'order-manager@eshop.com')->first(); // this user can not create user.

        Passport::actingAs($adminUser);
        $this->delete('/api/v1/users/4')->assertStatus(200);

        Passport::actingAs($userManagerUser);
        $this->delete('/api/v1/users/5')->assertStatus(200);

        Passport::actingAs($orderUser);
        $this->delete('/api/v1/users/1')->assertStatus(403);
    }

    /**
     * Customer can update only his profile and user manager only can delete users. 
     */
    public function test_customer_can_update_only_his_profile()
    {
        $customerUser = User::where('email', 'customer@eshop.com')->first();
        $payload = [
            'email' => 'customer2@eshop.com',
        ];
        Passport::actingAs($customerUser);
        $this->putJson('/api/v1/users/' . $customerUser->id, $payload)->assertStatus(200);
    }
}
