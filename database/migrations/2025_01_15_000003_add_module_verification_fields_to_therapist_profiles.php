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
            // Add module verification status fields
            $table->boolean('home_visit_verified')->default(false)->after('can_access_instructor');
            $table->boolean('courses_verified')->default(false)->after('home_visit_verified');
            $table->boolean('clinic_verified')->default(false)->after('courses_verified');
            $table->timestamp('home_visit_verified_at')->nullable()->after('clinic_verified');
            $table->timestamp('courses_verified_at')->nullable()->after('home_visit_verified_at');
            $table->timestamp('clinic_verified_at')->nullable()->after('courses_verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('therapist_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'home_visit_verified',
                'courses_verified',
                'clinic_verified',
                'home_visit_verified_at',
                'courses_verified_at',
                'clinic_verified_at'
            ]);
        });
    }
};

