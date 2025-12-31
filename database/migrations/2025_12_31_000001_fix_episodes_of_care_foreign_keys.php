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
        if (Schema::hasTable('episodes_of_care')) {
            // Drop existing foreign keys for patient_id and clinic_id
            // Try multiple methods to ensure we drop them regardless of naming convention
            Schema::table('episodes_of_care', function (Blueprint $table) {
                // Try dropping by column name (Laravel's default method)
                try {
                    $table->dropForeign(['patient_id']);
                } catch (\Exception $e) {
                    // Try common constraint name patterns
                    try {
                        $table->dropForeign('episodes_patient_id_foreign');
                    } catch (\Exception $e2) {
                        try {
                            $table->dropForeign('episodes_of_care_patient_id_foreign');
                        } catch (\Exception $e3) {
                            // Foreign key might not exist, continue
                        }
                    }
                }
                
                try {
                    $table->dropForeign(['clinic_id']);
                } catch (\Exception $e) {
                    try {
                        $table->dropForeign('episodes_clinic_id_foreign');
                    } catch (\Exception $e2) {
                        try {
                            $table->dropForeign('episodes_of_care_clinic_id_foreign');
                        } catch (\Exception $e3) {
                            // Foreign key might not exist, continue
                        }
                    }
                }
            });
            
            // Add correct foreign keys
            Schema::table('episodes_of_care', function (Blueprint $table) {
                // patient_id should reference patients table, not users
                if (Schema::hasTable('patients') && Schema::hasColumn('episodes_of_care', 'patient_id')) {
                    try {
                        $table->foreign('patient_id')
                            ->references('id')
                            ->on('patients')
                            ->onDelete('cascade');
                    } catch (\Exception $e) {
                        // Foreign key might already exist
                    }
                }
                
                // clinic_id should reference clinics table, not users
                if (Schema::hasTable('clinics') && Schema::hasColumn('episodes_of_care', 'clinic_id')) {
                    try {
                        $table->foreign('clinic_id')
                            ->references('id')
                            ->on('clinics')
                            ->onDelete('cascade');
                    } catch (\Exception $e) {
                        // Foreign key might already exist
                    }
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('episodes_of_care')) {
            Schema::table('episodes_of_care', function (Blueprint $table) {
                // Drop the correct foreign keys
                $foreignKeys = DB::select("
                    SELECT CONSTRAINT_NAME 
                    FROM information_schema.KEY_COLUMN_USAGE 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = 'episodes_of_care' 
                    AND CONSTRAINT_NAME LIKE '%patient_id%'
                ");
                
                if (!empty($foreignKeys)) {
                    $table->dropForeign(['patient_id']);
                }
                
                $clinicForeignKeys = DB::select("
                    SELECT CONSTRAINT_NAME 
                    FROM information_schema.KEY_COLUMN_USAGE 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = 'episodes_of_care' 
                    AND CONSTRAINT_NAME LIKE '%clinic_id%'
                ");
                
                if (!empty($clinicForeignKeys)) {
                    $table->dropForeign(['clinic_id']);
                }
                
                // Restore old foreign keys (if needed)
                if (Schema::hasTable('users')) {
                    $table->foreign('patient_id')
                        ->references('id')
                        ->on('users')
                        ->onDelete('cascade');
                    
                    $table->foreign('clinic_id')
                        ->references('id')
                        ->on('users')
                        ->onDelete('cascade');
                }
            });
        }
    }
};

