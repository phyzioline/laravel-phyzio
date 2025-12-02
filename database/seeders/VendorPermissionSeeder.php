<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;


class VendorPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $permissions = [
            'products-index','products-show','products-create','products-update','products-delete',
            'orders-index','orders-show','orders-create','orders-update','orders-delete',
         ];

        $adminRole = Role::firstOrCreate(['name' => 'vendor', 'guard_name' => 'web']);
        $adminRole->syncPermissions($permissions);
    }
}
