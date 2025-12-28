<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Creates episodes_of_care table (EpisodeOfCare model expects this table name)
     * If 'episodes' table exists, we'll copy data and rename, otherwise create new.
     */
    public function up(): void
    {
        // Check if episodes table exists but episodes_of_care doesn't
        if (Schema::hasTable('episodes') && !Schema::hasTable('episodes_of_care')) {
            // Drop foreign keys from dependent tables first (only if they exist)
            if (Schema::hasTable('assessments')) {
                // Check if foreign key exists before trying to drop it
                $foreignKeys = DB::select("
                    SELECT CONSTRAINT_NAME 
                    FROM information_schema.KEY_COLUMN_USAGE 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = 'assessments' 
                    AND CONSTRAINT_NAME LIKE '%episode_id%'
                ");
                
                if (!empty($foreignKeys)) {
                    Schema::table('assessments', function (Blueprint $table) {
                        $table->dropForeign(['episode_id']);
                    });
                }
            }
            if (Schema::hasTable('clinic_treatment_plans')) {
                // Check if foreign key exists before trying to drop it
                $foreignKeys = DB::select("
                    SELECT CONSTRAINT_NAME 
                    FROM information_schema.KEY_COLUMN_USAGE 
                    WHERE TABLE_SCHEMA = DATABASE() 
                    AND TABLE_NAME = 'clinic_treatment_plans' 
                    AND CONSTRAINT_NAME LIKE '%episode_id%'
                ");
                
                if (!empty($foreignKeys)) {
                    Schema::table('clinic_treatment_plans', function (Blueprint $table) {
                        $table->dropForeign(['episode_id']);
                    });
                }
            }
            
            // Rename episodes to episodes_of_care
            Schema::rename('episodes', 'episodes_of_care');
            
            // Add missing columns if they don't exist
            Schema::table('episodes_of_care', function (Blueprint $table) {
                if (!Schema::hasColumn('episodes_of_care', 'specialty')) {
                    $table->string('specialty')->nullable()->after('primary_therapist_id');
                }
                if (!Schema::hasColumn('episodes_of_care', 'diagnosis_icd')) {
                    $table->string('diagnosis_icd')->nullable()->after('specialty');
                }
                if (!Schema::hasColumn('episodes_of_care', 'chief_complaint')) {
                    $table->text('chief_complaint')->nullable()->after('diagnosis_icd');
                }
            });
            
            // Re-add foreign keys pointing to episodes_of_care
            if (Schema::hasTable('assessments')) {
                Schema::table('assessments', function (Blueprint $table) {
                    $table->foreign('episode_id')->references('id')->on('episodes_of_care')->onDelete('cascade');
                });
            }
            if (Schema::hasTable('clinic_treatment_plans')) {
                Schema::table('clinic_treatment_plans', function (Blueprint $table) {
                    $table->foreign('episode_id')->references('id')->on('episodes_of_care')->onDelete('cascade');
                });
            }
        } elseif (!Schema::hasTable('episodes_of_care')) {
            // Create episodes_of_care table if neither exists
            Schema::create('episodes_of_care', function (Blueprint $table) {
                $table->id();
                $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('clinic_id')->nullable()->constrained('users')->onDelete('cascade');
                $table->foreignId('primary_therapist_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('specialty')->nullable(); // orthopedic, pediatric, neurological, sports
                $table->string('diagnosis_icd')->nullable();
                $table->text('chief_complaint')->nullable();
                $table->string('title')->nullable(); // e.g., "Post-Surgery Recovery"
                $table->string('status')->default('active'); // active, discharged, on-hold
                $table->date('start_date');
                $table->date('end_date')->nullable();
                $table->text('discharge_summary')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't drop if episodes table is being used elsewhere
        // Just rename back if needed
        if (Schema::hasTable('episodes_of_care') && !Schema::hasTable('episodes')) {
            Schema::rename('episodes_of_care', 'episodes');
        }
    }
};

