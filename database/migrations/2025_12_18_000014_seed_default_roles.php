<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Insert default roles if the roles table exists
        if (Schema::hasTable('roles')) {
            $roles = ['admin', 'vendor', 'therapist', 'patient', 'company'];
            foreach ($roles as $role) {
                DB::table('roles')->insertOrIgnore([
                    'name' => $role,
                    'guard_name' => 'web',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('roles')) {
            DB::table('roles')->whereIn('name', ['admin', 'vendor', 'therapist', 'patient', 'company'])->delete();
        }
    }
};
