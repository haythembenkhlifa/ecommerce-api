<?php

namespace Modules\Order\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Product\Models\Product;

class OrderProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Order\Models\OrderProduct::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'order_id' => rand(1, 1000),
            'product_id' => rand(1, 1000),
            'quantity' => rand(1, 10),
        ];
    }
}
