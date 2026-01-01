<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Only proceed if job_requirements table exists
        if (!Schema::hasTable('job_requirements')) {
            return;
        }

        try {
            // Try to drop the old foreign key if it exists
            // Common constraint names in Laravel
            $constraintNames = [
                'job_requirements_job_id_foreign',
                'job_requirements_job_id_foreign',
            ];

            foreach ($constraintNames as $constraintName) {
                try {
                    DB::statement("ALTER TABLE job_requirements DROP FOREIGN KEY {$constraintName}");
                } catch (\Exception $e) {
                    // Constraint doesn't exist or has different name, continue
                }
            }

            // Also try to find and drop any foreign key on job_id
            $foreignKeys = DB::select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = DATABASE() 
                AND TABLE_NAME = 'job_requirements' 
                AND COLUMN_NAME = 'job_id' 
                AND REFERENCED_TABLE_NAME IS NOT NULL
            ");

            foreach ($foreignKeys as $fk) {
                try {
                    DB::statement("ALTER TABLE job_requirements DROP FOREIGN KEY {$fk->CONSTRAINT_NAME}");
                } catch (\Exception $e) {
                    // Ignore errors
                }
            }

            // Add the correct foreign key
            Schema::table('job_requirements', function (Blueprint $table) {
                $table->foreign('job_id')->references('id')->on('clinic_jobs')->onDelete('cascade');
            });
        } catch (\Exception $e) {
            // If clinic_jobs doesn't exist yet, that's okay - migration will handle it
            \Log::info('Could not fix job_requirements foreign key: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration is safe to reverse - it just fixes constraints
    }
};

