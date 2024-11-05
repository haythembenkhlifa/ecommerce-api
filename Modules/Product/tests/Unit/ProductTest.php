<?php

namespace Modules\Product\Tests\Unit;

use Laravel\Passport\Passport;
use Modules\Product\Models\Product;
use Modules\User\Models\User;
use Tests\TestCase;

class ProductTest extends TestCase
{
    /** 
     * Admin and product manager only can create products.
     */
    public function test_admin_and_product_manager_only_can_create_a_product()
    {

        $adminUser = User::where('email', 'admin@eshop.com')->first();
        $productManagerUser = User::where('email', 'product-manager@eshop.com')->first();
        $orderManagerUser = User::where('email', 'order-manager@eshop.com')->first();

        $payload1 = [
            "name" => "T-shirt",
            "description" => "White t-shirt.",
            "stock" => 99,
            "price" => 10,
        ];

        $payload2 = [
            "name" => "short",
            "description" => "White short.",
            "stock" => 99,
            "price" => 10,
        ];

        $payload3 = [
            "name" => "jean",
            "description" => "black jean.",
            "stock" => 100,
            "price" => 20,
        ];

        Passport::actingAs($adminUser);
        $this->postJson('/api/v1/products', $payload1)->assertStatus(201);
        $this->assertDatabaseHas('products', [
            'name' => 'T-shirt'
        ]);

        Passport::actingAs($productManagerUser);
        $this->postJson('/api/v1/products', $payload2)->assertStatus(201);
        $this->assertDatabaseHas('products', [
            'name' => 'short'
        ]);

        Passport::actingAs($orderManagerUser);
        $this->postJson('/api/v1/products', $payload3)->assertStatus(403);
    }

    /**
     * Admin and product manager only can updates products.
     */
    public function test_admin_and_product_manger_only_can_update_a_product()
    {
        $adminUser = User::where('email', 'admin@eshop.com')->first();
        $productManagerUser = User::where('email', 'product-manager@eshop.com')->first();
        $orderManagerUser = User::where('email', 'order-manager@eshop.com')->first();

        Passport::actingAs($adminUser);
        $this->putJson('/api/v1/products/1', ['name' => 'T-shirt'])->assertStatus(200);
        $this->assertDatabaseHas('products', ['name' => 'T-shirt']);

        Passport::actingAs($productManagerUser);
        $this->putJson('/api/v1/products/1', ['name' => 'short'])->assertStatus(200);
        $this->assertDatabaseHas('products', ['name' => 'short']);

        Passport::actingAs($orderManagerUser);
        $this->putJson('/api/v1/products/1', ['name' => 'pant'])->assertStatus(403);
    }

    /**
     * Admin and product manager only can delete products. 
     */
    public function test_admin_can_delete_a_product()
    {
        $adminUser = User::where('email', 'admin@eshop.com')->first();
        $productManagerUser = User::where('email', 'product-manager@eshop.com')->first();
        $orderManagerUser = User::where('email', 'order-manager@eshop.com')->first();

        Passport::actingAs($adminUser);
        $this->delete('/api/v1/products/1')->assertStatus(200);

        Passport::actingAs($productManagerUser);
        $this->delete('/api/v1/products/2')->assertStatus(200);

        Passport::actingAs($orderManagerUser);
        $this->delete('/api/v1/products/3')->assertStatus(403);
    }
}
