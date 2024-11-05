<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Delete cache permission
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Roles
        $adminRole = Role::create(['name' => 'admin']);
        $productManagerRole = Role::create(['name' => 'product-manager']);
        $orderManagerRole = Role::create(['name' => 'order-manager']);
        $userManagerRole = Role::create(['name' => 'user-manager']);
        $customerRole = Role::create(['name' => 'customer']);

        $permissions = [];
        $genericPermissions = ['viewAny', 'view', 'create', 'update', 'delete'];

        // Create permissions
        foreach ($genericPermissions as $genericPermission) {
            foreach (['users', 'products', 'orders'] as $model) {
                $permissions[] = $genericPermission . ' ' . $model;
                Permission::findOrCreate($genericPermission . ' ' . $model, 'api');
            }
        }



        $adminRole->givePermissionTo($permissions); // full access 

        $productManagerRole->givePermissionTo([
            'view users',
            'viewAny products',
            'view products',
            'create products',
            'update products',
            'delete products',
        ]);

        $orderManagerRole->givePermissionTo([
            'view users',
            'viewAny orders',
            'view orders',
            'create orders',
            'update orders',
            'delete orders',
        ]);

        $userManagerRole->givePermissionTo([
            'view users',
            'viewAny users',
            'view users',
            'create users',
            'update users',
            'delete users',
        ]);

        $customerRole->givePermissionTo([
            'view users',
            'update users',
            'create orders',
            'viewAny orders',
            'view orders',
            'viewAny products',
            'view products',
        ]);
    }
}
