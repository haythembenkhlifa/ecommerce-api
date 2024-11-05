<?php

namespace Modules\Order\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

use function Laravel\Prompts\text;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Order\Models\Order::class;


    protected $cities = [
        'Tunis',
        'Sfax',
        'Sousse',
        'Kairouan',
        'Bizerte',
        'Medenine',
        'Tunis',
        'Gabes',
        'Ariana',
        'Nabeul'
    ];

    protected $provinces = [
        'Ariana',
        'Béja',
        'Ben Arous',
        'Bizerte',
        'Gabès',
        'Jendouba',
        'Kairouan',
        'Kasserine',
        'Kebili',
        'Medenine',
        'Monastir',
        'Nabeul',
        'Sfax',
        'Sidi Bouzid',
        'Siliana',
        'Tunis',
        'Zaghouan'
    ];

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {

        return [
            'user_id' => rand(1, 100),
            'shipping_address_line_1' => $this->faker->streetAddress,
            'shipping_address_line_2' => $this->faker->optional()->secondaryAddress,
            'shipping_city' => $this->faker->randomElement($this->cities),
            'shipping_state' => $this->faker->randomElement($this->provinces),
            'shipping_postal_code' => $this->faker->numberBetween(1000, 9999),
            'billing_address_line_1' => $this->faker->streetAddress,
            'billing_address_line_2' => $this->faker->optional()->secondaryAddress,
            'billing_city' => $this->faker->randomElement($this->cities),
            'billing_state' => $this->faker->randomElement($this->provinces),
            'note' => $this->faker->text(),
        ];
    }
}
