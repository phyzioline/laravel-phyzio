<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Episodes of Care
        if (!Schema::hasTable('episodes')) {
            Schema::create('episodes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
                $table->foreignId('clinic_id')->nullable()->constrained('users')->onDelete('cascade');
                $table->foreignId('primary_therapist_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('title'); // e.g., "Post-Surgery Recovery"
                $table->string('status')->default('active'); // active, discharged, on-hold
                $table->date('start_date');
                $table->date('end_date')->nullable();
                $table->text('discharge_summary')->nullable();
                $table->timestamps();
            });
        }

        // 2. Clinical Assessments
        if (!Schema::hasTable('assessments')) {
            Schema::create('assessments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('episode_id')->constrained('episodes')->onDelete('cascade');
                $table->string('type'); // initial, progress, discharge
                $table->json('data'); // Flexible JSON for various assessment forms
                $table->foreignId('assessed_by')->constrained('users');
                $table->timestamp('assessed_at');
                $table->timestamps();
            });
        }

        // 3. Treatment Plans (Consolidated)
        if (!Schema::hasTable('clinic_treatment_plans')) {
            Schema::create('clinic_treatment_plans', function (Blueprint $table) {
                $table->id();
                $table->foreignId('episode_id')->constrained('episodes')->onDelete('cascade');
                $table->text('goals');
                $table->text('interventions');
                $table->string('frequency'); // e.g., "2x per week"
                $table->date('review_date')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('clinic_treatment_plans');
        Schema::dropIfExists('assessments');
        Schema::dropIfExists('episodes');
    }
};
