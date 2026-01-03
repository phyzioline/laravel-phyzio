<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('treatment_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            
            // Plan configuration
            $table->enum('clinic_type', ['pediatric', 'neurological', 'adult', 'multi_specialty'])->default('adult');
            $table->integer('plan_duration_weeks')->default(4);
            $table->enum('frequency', ['daily', 'three_per_week', 'two_per_week', 'weekly'])->default('weekly');
            $table->json('frequency_days')->nullable(); // e.g., [1,3,5] for Mon,Wed,Fri
            
            // Session configuration
            $table->enum('allowed_session_types', ['regular', 'intensive', 'both'])->default('regular');
            $table->integer('session_duration_minutes')->default(60);
            $table->integer('intensive_hours')->nullable(); // For intensive sessions: 1-4 hours
            
            // Status
            $table->enum('status', ['draft', 'active', 'completed', 'cancelled'])->default('draft');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('treatment_plans');
    }
};

