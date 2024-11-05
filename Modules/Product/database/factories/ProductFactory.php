<?php

namespace Modules\Product\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Product\Models\Product::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {

        $faker = \Faker\Factory::create();
        $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));

        return [
            'name' => $faker->productName,
            'description' => $faker->realText(),
            'price' => $this->faker->randomFloat(2, 5, 100),
            'stock' => $this->faker->numberBetween(0, 500),

        ];
    }
}
