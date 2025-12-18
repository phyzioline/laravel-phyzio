<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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
            'therapist_profiles-index', 'therapist_profiles-show', 'therapist_profiles-create', 'therapist_profiles-update', 'therapist_profiles-delete',
            'appointments-index', 'appointments-show', 'appointments-create', 'appointments-update', 'appointments-delete',
            'clinic_profiles-index', 'clinic_profiles-show', 'clinic_profiles-create', 'clinic_profiles-update', 'clinic_profiles-delete',
            'courses-index', 'courses-show', 'courses-create', 'courses-update', 'courses-delete',
            'jobs-index', 'jobs-show', 'jobs-create', 'jobs-update', 'jobs-delete',
            'data_points-index', 'data_points-show', 'data_points-create', 'data_points-update', 'data_points-delete',




        ];
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Auto-assign to Admin
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole) {
            $adminRole->syncPermissions(Permission::all());
        }
    }
}
