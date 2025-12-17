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
        Schema::table('therapist_profiles', function (Blueprint $table) {
            if (!Schema::hasColumn('therapist_profiles', 'skills_matrix')) {
                $table->json('skills_matrix')->nullable(); 
            }
            if (!Schema::hasColumn('therapist_profiles', 'equipment_proficiency')) {
                $table->json('equipment_proficiency')->nullable();
            }
            if (!Schema::hasColumn('therapist_profiles', 'case_experience_log')) {
                $table->json('case_experience_log')->nullable();
            }
            if (!Schema::hasColumn('therapist_profiles', 'verified_at')) {
                $table->timestamp('verified_at')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('therapist_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'skills_matrix',
                'equipment_proficiency',
                'case_experience_log',
                'verified_at',
            ]);
        });
    }
};
