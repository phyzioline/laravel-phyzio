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
        Schema::create('assessment_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->nullable()->constrained('clinics')->onDelete('cascade');
            $table->string('name'); // e.g., "Low Back Pain", "Knee OA", "Post-ACL Rehab"
            $table->string('condition_code')->nullable(); // ICD-10 or custom code
            $table->text('description')->nullable();
            $table->string('specialty')->nullable(); // orthopedic, sports, etc.
            
            // Template structure (JSON)
            $table->json('subjective_fields'); // Questions for subjective assessment
            $table->json('objective_fields'); // Objective measurements
            $table->json('pain_scale_config')->nullable(); // Pain scale settings
            $table->json('rom_config')->nullable(); // ROM measurement config
            $table->json('special_tests')->nullable(); // Special orthopedic tests
            $table->json('treatment_plan_suggestions')->nullable(); // Suggested treatment plans
            
            // Metadata
            $table->boolean('is_system_template')->default(false); // System-wide vs clinic-specific
            $table->boolean('is_active')->default(true);
            $table->integer('usage_count')->default(0);
            
            $table->timestamps();
            
            // Indexes
            $table->index('clinic_id');
            $table->index('specialty');
            $table->index('is_system_template');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assessment_templates');
    }
};

