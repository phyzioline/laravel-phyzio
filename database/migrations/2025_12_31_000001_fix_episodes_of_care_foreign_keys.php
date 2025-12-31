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
            // First, check if foreign keys exist before trying to drop them
            Schema::table('episodes_of_care', function (Blueprint $table) {
                // Check and drop patient_id foreign key
                $patientForeignKeys = DB::select("
                    SELECT CONSTRAINT_NAME 
                    FROM information_schema.KEY_COLUMN_USAGE 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = 'episodes_of_care' 
                    AND COLUMN_NAME = 'patient_id'
                    AND REFERENCED_TABLE_NAME IS NOT NULL
                ");
                
                if (!empty($patientForeignKeys)) {
                    foreach ($patientForeignKeys as $fk) {
                        try {
                            $table->dropForeign($fk->CONSTRAINT_NAME);
                        } catch (\Exception $e) {
                            // Try dropping by column name as fallback
                            try {
                                $table->dropForeign(['patient_id']);
                            } catch (\Exception $e2) {
                                // Foreign key might not exist or already dropped, continue
                            }
                        }
                    }
                }
                
                // Check and drop clinic_id foreign key
                $clinicForeignKeys = DB::select("
                    SELECT CONSTRAINT_NAME 
                    FROM information_schema.KEY_COLUMN_USAGE 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = 'episodes_of_care' 
                    AND COLUMN_NAME = 'clinic_id'
                    AND REFERENCED_TABLE_NAME IS NOT NULL
                ");
                
                if (!empty($clinicForeignKeys)) {
                    foreach ($clinicForeignKeys as $fk) {
                        try {
                            $table->dropForeign($fk->CONSTRAINT_NAME);
                        } catch (\Exception $e) {
                            // Try dropping by column name as fallback
                            try {
                                $table->dropForeign(['clinic_id']);
                            } catch (\Exception $e2) {
                                // Foreign key might not exist or already dropped, continue
                            }
                        }
                    }
                }
            });
            
            // Add correct foreign keys
            Schema::table('episodes_of_care', function (Blueprint $table) {
                // patient_id should reference patients table, not users
                if (Schema::hasTable('patients') && Schema::hasColumn('episodes_of_care', 'patient_id')) {
                    // Check if foreign key already exists pointing to patients
                    $existingFk = DB::select("
                        SELECT CONSTRAINT_NAME 
                        FROM information_schema.KEY_COLUMN_USAGE 
                        WHERE TABLE_SCHEMA = DATABASE() 
                        AND TABLE_NAME = 'episodes_of_care' 
                        AND COLUMN_NAME = 'patient_id'
                        AND REFERENCED_TABLE_NAME = 'patients'
                    ");
                    
                    if (empty($existingFk)) {
                        try {
                            $table->foreign('patient_id')
                                ->references('id')
                                ->on('patients')
                                ->onDelete('cascade');
                        } catch (\Exception $e) {
                            // Foreign key might already exist, continue
                        }
                    }
                }
                
                // clinic_id should reference clinics table, not users
                if (Schema::hasTable('clinics') && Schema::hasColumn('episodes_of_care', 'clinic_id')) {
                    // Check if foreign key already exists pointing to clinics
                    $existingFk = DB::select("
                        SELECT CONSTRAINT_NAME 
                        FROM information_schema.KEY_COLUMN_USAGE 
                        WHERE TABLE_SCHEMA = DATABASE() 
                        AND TABLE_NAME = 'episodes_of_care' 
                        AND COLUMN_NAME = 'clinic_id'
                        AND REFERENCED_TABLE_NAME = 'clinics'
                    ");
                    
                    if (empty($existingFk)) {
                        try {
                            $table->foreign('clinic_id')
                                ->references('id')
                                ->on('clinics')
                                ->onDelete('cascade');
                        } catch (\Exception $e) {
                            // Foreign key might already exist, continue
                        }
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

