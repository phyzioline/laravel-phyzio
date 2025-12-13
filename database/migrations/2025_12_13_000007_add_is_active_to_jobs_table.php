<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('jobs')) {
            Schema::table('jobs', function (Blueprint $table) {
                if (!Schema::hasColumn('jobs', 'is_active')) {
                    $table->boolean('is_active')->default(true)->after('id');
                }
                
                // Also ensure other potentially missing columns are there just in case
                if (!Schema::hasColumn('jobs', 'file_path')) {
                    $table->string('file_path')->nullable();
                }
                if (!Schema::hasColumn('jobs', 'clinic_id')) {
                    $table->foreignId('clinic_id')->nullable()->constrained('users')->onDelete('cascade');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('jobs')) {
            Schema::table('jobs', function (Blueprint $table) {
                if (Schema::hasColumn('jobs', 'is_active')) {
                    $table->dropColumn('is_active');
                }
            });
        }
    }
};
