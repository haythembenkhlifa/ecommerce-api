<?php

namespace Modules\Order\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Modules\Order\Models\Order;
use Modules\Product\Models\Product;
use Modules\User\Models\User;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Indicates whether the default seeder should run before each test.
     *
     * @var bool
     */
    protected $seed = true;

    /** 
     * Admin, order manager and customer only can create products.
     */
    public function test_admin_and_order_manager_and_customer_only_can_create_orders()
    {

        $adminUser = User::where('email', 'admin@eshop.com')->first();
        $orderManagerUser = User::where('email', 'order-manager@eshop.com')->first();
        $customerUser = User::where('email', 'customer@eshop.com')->first();
        $productManagerUser = User::where('email', 'product-manager@eshop.com')->first();

        $payload1 = [
            "payment_method" => "card",
            "shipping_address_line_1" => "123 ave habib bourguiba",
            "shipping_city" => "Tunis",
            "shipping_state" => "Kairouan",
            "shipping_postal_code" => "9227",
            "products" => [
                [
                    "product_id" => 1,
                    "quantity" => 1
                ]
            ]
        ];

        Passport::actingAs($adminUser);
        $this->postJson('/api/v1/orders', $payload1)->assertStatus(201);
        $this->assertDatabaseHas('orders', [
            "shipping_address_line_1" => "123 ave habib bourguiba",
        ]);

        $payload2 = array_merge($payload1, ["shipping_address_line_1" => "124 ave habib bourguiba"]);
        Passport::actingAs($orderManagerUser);
        $this->postJson('/api/v1/orders', $payload2)->assertStatus(201);
        $this->assertDatabaseHas('orders', [
            "shipping_address_line_1" => "124 ave habib bourguiba",
        ]);

        $payload3 = array_merge($payload1, ["shipping_address_line_1" => "125 ave habib bourguiba"]);
        Passport::actingAs($customerUser);
        $this->postJson('/api/v1/orders', $payload3)->assertStatus(201);
        $this->assertDatabaseHas('orders', [
            "shipping_address_line_1" => "125 ave habib bourguiba",
        ]);

        Passport::actingAs($productManagerUser);
        $this->postJson('/api/v1/orders', $payload1)->assertStatus(403);
    }


    /** 
     * Admin, order manager and customer only can create batch products.
     */
    public function test_admin_and_order_manager_and_customer_only_can_create_batch_orders()
    {

        $adminUser = User::where('email', 'admin@eshop.com')->first();
        $orderManagerUser = User::where('email', 'order-manager@eshop.com')->first();
        $customerUser = User::where('email', 'customer@eshop.com')->first();
        $productManagerUser = User::where('email', 'product-manager@eshop.com')->first();

        $batch["orders"] = [
            "orders" => [
                "payment_method" => "card",
                "shipping_address_line_1" => "123 ave habib bourguiba",
                "shipping_city" => "Tunis",
                "shipping_state" => "Kairouan",
                "shipping_postal_code" => "9227",
                "products" => [
                    [
                        "product_id" => 1,
                        "quantity" => 1
                    ]
                ]
            ],
            [
                "payment_method" => "card",
                "shipping_address_line_1" => "123 ave habib bourguiba",
                "shipping_city" => "Tunis",
                "shipping_state" => "Kairouan",
                "shipping_postal_code" => "9227",
                "products" => [
                    [
                        "product_id" => 1,
                        "quantity" => 1
                    ]
                ]
            ],
            [
                "payment_method" => "card",
                "shipping_address_line_1" => "123 ave habib bourguiba",
                "shipping_city" => "Tunis",
                "shipping_state" => "Kairouan",
                "shipping_postal_code" => "9227",
                "products" => [
                    [
                        "product_id" => 1,
                        "quantity" => 1
                    ]
                ]
            ]
        ];


        Passport::actingAs($adminUser);
        $this->postJson('/api/v1/orders/batch', $batch)->assertStatus(200);
        $this->assertDatabaseCount('orders', 3);

        Passport::actingAs($orderManagerUser);
        $this->postJson('/api/v1/orders/batch', $batch)->assertStatus(200);
        $this->assertDatabaseCount('orders', 6);

        Passport::actingAs($customerUser);
        $this->postJson('/api/v1/orders/batch', $batch)->assertStatus(200);
        $this->assertDatabaseCount('orders', 9);

        Passport::actingAs($productManagerUser);
        $this->postJson('/api/v1/orders', $batch)->assertStatus(403);
    }

    /**
     * Admin and order manager only can updates orders.
     */
    public function test_admin_and_order_manger_only_can_update_orders()
    {
        $adminUser = User::where('email', 'admin@eshop.com')->first();
        $orderManagerUser = User::where('email', 'order-manager@eshop.com')->first();
        $customerUser = User::where('email', 'customer@eshop.com')->first();
        $productManagerUser = User::where('email', 'product-manager@eshop.com')->first();

        $payload1 = [
            "payment_method" => "card",
            "shipping_address_line_1" => "123 ave habib bourguiba",
            "shipping_city" => "Tunis",
            "shipping_state" => "Kairouan",
            "shipping_postal_code" => "9227",
            "products" => [
                [
                    "product_id" => 1,
                    "quantity" => 1
                ]
            ]
        ];
        Order::factory(1)->create();

        Passport::actingAs($adminUser);
        $this->putJson('/api/v1/orders/1', $payload1)->assertStatus(200);
        $this->assertDatabaseHas('orders', [
            "shipping_address_line_1" => "123 ave habib bourguiba",
        ]);

        $payload2 = array_merge($payload1, ["shipping_address_line_1" => "124 ave habib bourguiba"]);
        Passport::actingAs($orderManagerUser);
        $this->putJson('/api/v1/orders/1', $payload2)->assertStatus(200);
        $this->assertDatabaseHas('orders', [
            "shipping_address_line_1" => "124 ave habib bourguiba",
        ]);

        Passport::actingAs($productManagerUser);
        $this->putJson('/api/v1/orders/1', $payload1)->assertStatus(404);
    }

    /**
     * Admin and order manager only can delete orders. 
     */
    public function test_admin_and_order_manager_can_delete_a_user()
    {
        $adminUser = User::where('email', 'admin@eshop.com')->first();
        $orderManagerUser = User::where('email', 'order-manager@eshop.com')->first();
        $customerUser = User::where('email', 'customer@eshop.com')->first();

        Order::factory(3)->create();

        Passport::actingAs($adminUser);
        $this->delete('/api/v1/orders/1')->assertStatus(200);

        Passport::actingAs($orderManagerUser);
        $this->delete('/api/v1/orders/2')->assertStatus(200);

        Passport::actingAs($customerUser);
        $this->delete('/api/v1/orders/3')->assertStatus(404);
    }
}
