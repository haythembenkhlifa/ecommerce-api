<?php

namespace Modules\Order\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Order\Models\Order;
use Modules\Order\Models\OrderProduct;
use Modules\Product\Models\Product;

class OrderDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Order::factory(1000)->create();

        $orders = Order::all();
        $products = Product::all();

        if ($orders->isEmpty() || $products->isEmpty()) {
            return; // Prevent seeding if there are no orders or products
        }

        foreach ($orders as $order) {
            $orderProducts = $products->random(rand(1, 5))->pluck('id')->toArray();

            foreach ($orderProducts as $productId) {
                OrderProduct::create([
                    'order_id' => $order->id,
                    'product_id' => $productId,
                    'quantity' => rand(1, 5), // Random quantity
                    'price' => $products->find($productId)->price, // Use the product's price
                ]);
            }
        }
    }
}
