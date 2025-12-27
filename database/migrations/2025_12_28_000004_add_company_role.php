<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add company role if it doesn't exist
        if (Schema::hasTable('roles')) {
            if (!Role::where('name', 'company')->where('guard_name', 'web')->exists()) {
                Role::create([
                    'name' => 'company',
                    'guard_name' => 'web',
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('roles')) {
            Role::where('name', 'company')->where('guard_name', 'web')->delete();
        }
    }
};

