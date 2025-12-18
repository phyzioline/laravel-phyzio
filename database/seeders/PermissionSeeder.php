<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'users-index','users-show','users-create','users-update','users-delete',
            'roles-index','roles-show','roles-create','roles-update','roles-delete',
            'vendors-index','vendors-show','vendors-create','vendors-update','vendors-delete',
            'buyers-index','buyers-show','buyers-create','buyers-update','buyers-delete',
            'notifications-index','notifications-show','notifications-create','notifications-update','notifications-delete',
            'privacy_policy-update',
            'send_notifications-index','send_notifications-show','send_notifications-create','send_notifications-update','send_notifications-delete',
            'products-index','products-show','products-create','products-update','products-delete',
            'categories-index','categories-show','categories-create','categories-update','categories-delete',
            'sub_categories-index','sub_categories-show','sub_categories-create','sub_categories-update','sub_categories-delete',
            'tags-index','tags-show','tags-create','tags-update','tags-delete',
            'orders-index','orders-show','orders-create','orders-update','orders-delete',
            'setting-update',
            'shipping_policy-update',
            'profile-update',
            'tearms_conditions-update',
            'privacy_policy-update',
            'financials-index',
            'reports-index',




        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }
    }
}
