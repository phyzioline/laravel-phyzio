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
            $table->json('skills_matrix')->nullable(); // { "manual_therapy": 5, "dry_needling": 3 }
            $table->json('equipment_proficiency')->nullable();
            $table->json('case_experience_log')->nullable();
            $table->timestamp('verified_at')->nullable();
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
