<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;

class UserDatabaseSeeder extends Seeder
{

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'John',
            'email' => 'admin@eshop.com',
            'password' => Hash::make('010203'),
            'phone_number' => rand(99000000, 99999999)
        ])->assignRole('admin');

        User::create([
            'name' => 'Mike',
            'email' => 'order-manager@eshop.com',
            'password' => Hash::make('010203'),
            'phone_number' => rand(99000000, 99999999)
        ])->assignRole('order-manager');

        User::create([
            'name' => 'Alex',
            'email' => 'user-manager@eshop.com',
            'password' => Hash::make('010203'),
            'phone_number' => rand(99000000, 99999999)
        ])->assignRole('user-manager');

        User::create([
            'name' => 'Sam',
            'email' => 'product-manager@eshop.com',
            'password' => Hash::make('010203'),
            'phone_number' => rand(99000000, 99999999)
        ])->assignRole('product-manager');

        User::create([
            'name' => 'Andrew',
            'email' => 'customer@eshop.com',
            'password' => Hash::make('010203'),
            'phone_number' => rand(99000000, 99999999)
        ])->assignRole('customer');
    }
}
