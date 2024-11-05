<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Modules\Order\Database\Seeders\OrderDatabaseSeeder;
use Modules\Product\Database\Seeders\ProductDatabaseSeeder;
use Modules\User\Database\Seeders\UserDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([
            RolesAndPermissionsSeeder::class,
            UserDatabaseSeeder::class,
            ProductDatabaseSeeder::class,
            // OrderDatabaseSeeder::class,
        ]);

        // Artisan::call('passport:client --personal --no-interaction');
    }
}
