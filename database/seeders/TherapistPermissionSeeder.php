<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class TherapistPermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create therapist-specific permissions
        $permissions = [
            // Profile Management
            'therapist_profile-view',
            'therapist_profile-update',
            
            // Appointments
            'appointments-view_own',
            'appointments-update_status',
            'appointments-cancel',
            
            // Earnings & Payouts
            'earnings-view_own',
            'earnings-request_payout',
            
            // Courses
            'courses-view',
            'courses-enroll',
            
            // Dashboard Access
            'dashboard-therapist_access',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Assign permissions to therapist role
        $therapistRole = Role::where('name', 'therapist')->first();
        if ($therapistRole) {
            $therapistRole->syncPermissions($permissions);
        }
    }
}
