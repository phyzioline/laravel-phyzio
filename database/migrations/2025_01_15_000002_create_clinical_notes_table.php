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
        // Determine which episodes table exists
        $episodeTable = Schema::hasTable('episodes_of_care') ? 'episodes_of_care' : (Schema::hasTable('episodes') ? 'episodes' : null);
        
        // Check if tables already exist (from previous failed migration)
        if (Schema::hasTable('clinical_notes')) {
            // Table exists, just add missing columns/foreign keys if needed
            Schema::table('clinical_notes', function (Blueprint $table) use ($episodeTable) {
                if (!Schema::hasColumn('clinical_notes', 'episode_id') && $episodeTable) {
                    $table->foreignId('episode_id')->nullable()->constrained($episodeTable)->onDelete('set null');
                }
            });
        } else {
            Schema::create('clinical_notes', function (Blueprint $table) use ($episodeTable) {
            $table->id();
            $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('appointment_id')->nullable()->constrained('clinic_appointments')->onDelete('set null');
            
            // Episode ID - use correct table name
            if ($episodeTable) {
                $table->foreignId('episode_id')->nullable()->constrained($episodeTable)->onDelete('set null');
            } else {
                $table->unsignedBigInteger('episode_id')->nullable();
            }
            
            $table->foreignId('therapist_id')->constrained('users')->onDelete('cascade');
            
            // Note Type & Template
            $table->string('note_type')->default('soap'); // soap, evaluation, progress, discharge
            $table->string('specialty'); // pediatric, neuro, ortho, geriatrics, women_health, sports
            $table->foreignId('template_id')->nullable()->constrained('clinical_templates')->onDelete('set null');
            
            // SOAP Components
            $table->text('subjective')->nullable(); // Patient-reported information
            $table->text('objective')->nullable(); // Observations, measurements
            $table->text('assessment')->nullable(); // Clinical interpretation
            $table->text('plan')->nullable(); // Treatment plan
            
            // Additional Clinical Data
            $table->json('chief_complaint')->nullable();
            $table->json('history_of_present_illness')->nullable();
            $table->json('review_of_systems')->nullable();
            $table->json('physical_examination')->nullable();
            $table->json('functional_assessment')->nullable();
            $table->json('outcome_measures')->nullable(); // ROM, strength, pain scales, etc.
            $table->json('treatment_performed')->nullable();
            $table->json('patient_response')->nullable();
            $table->text('clinical_impression')->nullable();
            $table->text('plan_of_care')->nullable();
            
            // Coding & Compliance
            $table->json('diagnosis_codes')->nullable(); // ICD-10 codes
            $table->json('procedure_codes')->nullable(); // CPT codes
            $table->json('modifiers')->nullable();
            $table->boolean('coding_validated')->default(false);
            $table->text('coding_errors')->nullable();
            $table->json('compliance_checks')->nullable(); // NCCI, 8-minute rule, etc.
            
            // AI & Automation
            $table->text('voice_transcription')->nullable();
            $table->text('ai_generated_notes')->nullable();
            $table->boolean('ai_assisted')->default(false);
            $table->json('ai_suggestions')->nullable();
            $table->json('clinical_recommendations')->nullable(); // From decision support
            
            // Status & Workflow
            $table->enum('status', ['draft', 'in_review', 'signed', 'locked'])->default('draft');
            $table->timestamp('signed_at')->nullable();
            $table->foreignId('signed_by')->nullable()->constrained('users')->onDelete('set null');
            
            // Metadata
            $table->text('notes')->nullable(); // Additional notes
            $table->json('custom_fields')->nullable(); // Specialty-specific fields
            $table->boolean('is_locked')->default(false);
            $table->timestamps();
            
            // Indexes
            $table->index(['clinic_id', 'patient_id']);
            $table->index(['appointment_id']);
            $table->index(['episode_id']);
            $table->index(['therapist_id', 'created_at']);
            $table->index(['specialty', 'note_type']);
            $table->index('status');
            });
        }

        if (!Schema::hasTable('clinical_templates')) {
            Schema::create('clinical_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->nullable()->constrained('clinics')->onDelete('cascade');
            $table->string('name');
            $table->string('specialty'); // pediatric, neuro, ortho, etc.
            $table->string('note_type'); // soap, evaluation, progress, discharge
            $table->text('description')->nullable();
            
            // Template Structure
            $table->json('fields_schema'); // Dynamic form fields
            $table->json('default_values')->nullable();
            $table->json('validation_rules')->nullable();
            $table->json('coding_rules')->nullable(); // Auto-coding logic
            
            // Clinical Decision Support
            $table->json('decision_support_rules')->nullable();
            $table->json('evidence_based_guidelines')->nullable();
            
            // Status
            $table->boolean('is_active')->default(true);
            $table->boolean('is_system_template')->default(false); // System vs custom
            $table->integer('usage_count')->default(0);
            
            $table->timestamps();
            
            $table->index(['specialty', 'note_type']);
            $table->index(['clinic_id', 'is_active']);
            });
        }

        if (!Schema::hasTable('clinical_timeline')) {
            Schema::create('clinical_timeline', function (Blueprint $table) use ($episodeTable) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
            
            // Episode ID - use correct table name
            if ($episodeTable) {
                $table->foreignId('episode_id')->nullable()->constrained($episodeTable)->onDelete('cascade');
            } else {
                $table->unsignedBigInteger('episode_id')->nullable();
            }
            
            $table->string('event_type'); // note_created, appointment_completed, assessment_added, etc.
            $table->string('title');
            $table->text('description')->nullable();
            $table->json('metadata')->nullable();
            
            // Related Records
            $table->morphs('related'); // Polymorphic: can link to notes, appointments, assessments, etc.
            
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamp('event_date');
            
            $table->timestamps();
            
            $table->index(['patient_id', 'event_date']);
            $table->index(['clinic_id', 'episode_id']);
            $table->index('event_type');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinical_timeline');
        Schema::dropIfExists('clinical_templates');
        Schema::dropIfExists('clinical_notes');
    }
};

