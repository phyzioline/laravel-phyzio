<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Creates comprehensive specialty-based clinical assessment tables
     */
    public function up(): void
    {
        // 1. Specialty Configuration Table
        Schema::create('pt_specialty_configs', function (Blueprint $table) {
            $table->id();
            $table->string('specialty_key')->unique(); // musculoskeletal, neurological, etc.
            $table->string('name_en');
            $table->string('name_ar')->nullable();
            $table->text('description_en')->nullable();
            $table->text('description_ar')->nullable();
            $table->json('assessment_components')->nullable(); // Subjective, Objective fields
            $table->json('outcome_measures')->nullable(); // Available outcome measures
            $table->json('tools_devices')->nullable(); // Required tools/devices
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Joint ROM (Range of Motion) Table - Musculoskeletal/Orthopedic
        Schema::create('joint_rom_measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('clinical_assessments')->onDelete('cascade');
            $table->foreignId('episode_id')->constrained('episodes_of_care')->onDelete('cascade');
            $table->string('joint_name'); // neck, shoulder, elbow, wrist, hip, knee, ankle, spine
            $table->string('movement'); // flexion, extension, abduction, adduction, rotation, etc.
            $table->enum('type', ['AROM', 'PROM']); // Active or Passive ROM
            $table->decimal('degrees', 5, 2)->nullable(); // ROM in degrees
            $table->text('end_feel')->nullable(); // normal, firm, hard, soft, empty
            $table->text('notes')->nullable();
            $table->timestamp('measured_at');
            $table->foreignId('therapist_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['assessment_id', 'joint_name']);
        });

        // 3. Muscle Strength Grades - Musculoskeletal/Orthopedic
        Schema::create('muscle_strength_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('clinical_assessments')->onDelete('cascade');
            $table->foreignId('episode_id')->constrained('episodes_of_care')->onDelete('cascade');
            $table->string('muscle_group'); // e.g., "Quadriceps", "Deltoid", "Biceps"
            $table->enum('method', ['MMT', 'Dynamometer', 'Functional']); // Manual Muscle Testing, Dynamometer, Functional
            $table->string('grade')->nullable(); // 0-5 for MMT, or numeric for dynamometer
            $table->decimal('force_value', 8, 2)->nullable(); // For dynamometer (kg or lbs)
            $table->enum('side', ['left', 'right', 'bilateral'])->default('bilateral');
            $table->text('notes')->nullable();
            $table->timestamp('measured_at');
            $table->foreignId('therapist_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['assessment_id', 'muscle_group']);
        });

        // 4. Special Orthopedic Tests - Musculoskeletal
        Schema::create('special_orthopedic_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('clinical_assessments')->onDelete('cascade');
            $table->foreignId('episode_id')->constrained('episodes_of_care')->onDelete('cascade');
            $table->string('test_name'); // e.g., "McMurray's Test", "Lachman Test", "Neer's Impingement"
            $table->string('body_region'); // shoulder, knee, hip, spine, etc.
            $table->enum('result', ['positive', 'negative', 'equivocal'])->nullable();
            $table->text('findings')->nullable();
            $table->enum('side', ['left', 'right', 'bilateral'])->nullable();
            $table->timestamp('performed_at');
            $table->foreignId('therapist_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['assessment_id', 'test_name']);
        });

        // 5. Pain Assessment - Universal
        Schema::create('pain_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('clinical_assessments')->onDelete('cascade');
            $table->foreignId('episode_id')->constrained('episodes_of_care')->onDelete('cascade');
            $table->enum('scale_type', ['VAS', 'NPRS', 'Faces', 'Functional']); // Visual Analog Scale, Numeric Pain Rating Scale
            $table->integer('score')->nullable(); // 0-10 for NPRS, 0-100 for VAS
            $table->text('location')->nullable(); // Where is the pain
            $table->enum('quality', ['sharp', 'dull', 'aching', 'burning', 'throbbing', 'stabbing'])->nullable();
            $table->text('aggravating_factors')->nullable();
            $table->text('relieving_factors')->nullable();
            $table->enum('timing', ['constant', 'intermittent', 'morning', 'evening', 'night'])->nullable();
            $table->timestamp('assessed_at');
            $table->foreignId('therapist_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // 6. Neurological Assessments
        Schema::create('neurological_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('clinical_assessments')->onDelete('cascade');
            $table->foreignId('episode_id')->constrained('episodes_of_care')->onDelete('cascade');
            $table->enum('tone_scale', ['0', '1', '1+', '2', '3', '4'])->nullable(); // Modified Ashworth Scale
            $table->text('reflexes')->nullable(); // Deep tendon reflexes findings
            $table->text('coordination_tests')->nullable(); // Finger-to-nose, heel-to-shin, etc.
            $table->decimal('balance_score', 5, 2)->nullable(); // Berg Balance Scale, etc.
            $table->text('gait_analysis')->nullable(); // Gait pattern, deviations
            $table->text('sensory_testing')->nullable(); // Light touch, proprioception, etc.
            $table->text('functional_level')->nullable(); // FIM level, etc.
            $table->timestamp('assessed_at');
            $table->foreignId('therapist_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // 7. Cardiopulmonary Assessments
        Schema::create('cardiopulmonary_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('clinical_assessments')->onDelete('cascade');
            $table->foreignId('episode_id')->constrained('episodes_of_care')->onDelete('cascade');
            $table->integer('heart_rate')->nullable();
            $table->integer('blood_pressure_systolic')->nullable();
            $table->integer('blood_pressure_diastolic')->nullable();
            $table->integer('respiratory_rate')->nullable();
            $table->integer('oxygen_saturation')->nullable(); // SpO2 percentage
            $table->decimal('chest_expansion', 5, 2)->nullable(); // cm
            $table->text('breath_sounds')->nullable(); // Clear, wheezing, crackles, etc.
            $table->integer('dyspnea_scale')->nullable(); // Borg Scale 0-10
            $table->integer('six_minute_walk_distance')->nullable(); // meters
            $table->text('exercise_tolerance')->nullable();
            $table->boolean('on_oxygen')->default(false);
            $table->timestamp('assessed_at');
            $table->foreignId('therapist_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // 8. Pediatric Assessments
        Schema::create('pediatric_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('clinical_assessments')->onDelete('cascade');
            $table->foreignId('episode_id')->constrained('episodes_of_care')->onDelete('cascade');
            $table->text('developmental_history')->nullable();
            $table->text('birth_history')->nullable();
            $table->json('gross_motor_milestones')->nullable(); // Age-appropriate milestones
            $table->json('primitive_reflexes')->nullable(); // Present/absent primitive reflexes
            $table->json('postural_reactions')->nullable();
            $table->decimal('gmfm_score', 5, 2)->nullable(); // Gross Motor Function Measure
            $table->decimal('pdms_score', 5, 2)->nullable(); // Peabody Developmental Motor Scales
            $table->text('age_adjusted_progress')->nullable();
            $table->timestamp('assessed_at');
            $table->foreignId('therapist_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // 9. Geriatric Assessments
        Schema::create('geriatric_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('clinical_assessments')->onDelete('cascade');
            $table->foreignId('episode_id')->constrained('episodes_of_care')->onDelete('cascade');
            $table->integer('fall_history_count')->default(0);
            $table->text('fall_circumstances')->nullable();
            $table->decimal('berg_balance_score', 5, 2)->nullable();
            $table->decimal('tinetti_score', 5, 2)->nullable();
            $table->decimal('tug_time', 5, 2)->nullable(); // Timed Up and Go in seconds
            $table->decimal('gait_speed', 5, 2)->nullable(); // m/s
            $table->text('mobility_aids')->nullable(); // Walker, cane, etc.
            $table->integer('independence_score')->nullable(); // 0-100
            $table->enum('fall_risk_level', ['low', 'moderate', 'high'])->nullable();
            $table->timestamp('assessed_at');
            $table->foreignId('therapist_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // 10. Sports Performance Assessments
        Schema::create('sports_performance_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('clinical_assessments')->onDelete('cascade');
            $table->foreignId('episode_id')->constrained('episodes_of_care')->onDelete('cascade');
            $table->string('sport_type')->nullable();
            $table->text('sport_specific_demands')->nullable();
            $table->decimal('hop_test_distance', 8, 2)->nullable(); // cm
            $table->decimal('limb_symmetry_index', 5, 2)->nullable(); // LSI percentage
            $table->decimal('agility_time', 5, 2)->nullable(); // seconds
            $table->decimal('power_output', 8, 2)->nullable(); // watts or force
            $table->enum('rtp_status', ['cleared', 'not_cleared', 'conditional'])->nullable(); // Return to play
            $table->text('performance_metrics')->nullable(); // JSON for various metrics
            $table->timestamp('assessed_at');
            $table->foreignId('therapist_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // 11. Women's Health / Pelvic Floor Assessments
        Schema::create('pelvic_floor_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('clinical_assessments')->onDelete('cascade');
            $table->foreignId('episode_id')->constrained('episodes_of_care')->onDelete('cascade');
            $table->integer('pelvic_strength_grade')->nullable(); // 0-5
            $table->integer('endurance_seconds')->nullable(); // How long can hold contraction
            $table->enum('continence_status', ['continent', 'stress_incontinence', 'urge_incontinence', 'mixed'])->nullable();
            $table->text('bladder_diary_summary')->nullable();
            $table->text('pain_mapping')->nullable();
            $table->decimal('pfdi_score', 5, 2)->nullable(); // Pelvic Floor Distress Inventory
            $table->decimal('pfiq_score', 5, 2)->nullable(); // Pelvic Floor Impact Questionnaire
            $table->text('postpartum_status')->nullable();
            $table->timestamp('assessed_at');
            $table->foreignId('therapist_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // 12. Pain Management Assessments
        Schema::create('pain_management_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('clinical_assessments')->onDelete('cascade');
            $table->foreignId('episode_id')->constrained('episodes_of_care')->onDelete('cascade');
            $table->enum('pain_type', ['acute', 'chronic', 'subacute'])->nullable();
            $table->text('pain_sensitization_findings')->nullable();
            $table->text('psychosocial_factors')->nullable();
            $table->integer('pain_catastrophizing_score')->nullable();
            $table->text('pain_profile')->nullable(); // Detailed pain characteristics
            $table->enum('chronicity_level', ['mild', 'moderate', 'severe'])->nullable();
            $table->text('treatment_response')->nullable();
            $table->timestamp('assessed_at');
            $table->foreignId('therapist_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // 13. Outcome Measures Tracking (Universal - for all specialties)
        Schema::create('outcome_measures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('clinical_assessments')->onDelete('cascade');
            $table->foreignId('episode_id')->constrained('episodes_of_care')->onDelete('cascade');
            $table->string('measure_name'); // ODI, NDI, DASH, FIM, Berg Balance, etc.
            $table->string('specialty_key')->nullable(); // Which specialty this belongs to
            $table->json('scores')->nullable(); // Can store multiple sub-scores
            $table->decimal('total_score', 8, 2)->nullable();
            $table->decimal('percentage', 5, 2)->nullable(); // If applicable
            $table->text('interpretation')->nullable();
            $table->enum('assessment_type', ['initial', 're_evaluation', 'discharge', 'follow_up'])->default('initial');
            $table->timestamp('assessed_at');
            $table->foreignId('therapist_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['episode_id', 'measure_name']);
            $table->index(['assessment_id', 'measure_name']);
        });

        // 14. Functional Score Tracking
        Schema::create('functional_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('clinical_assessments')->onDelete('cascade');
            $table->foreignId('episode_id')->constrained('episodes_of_care')->onDelete('cascade');
            $table->string('functional_task'); // ADL task, work task, sport task
            $table->enum('category', ['ADL', 'Work', 'Sport', 'Recreation', 'Other'])->default('ADL');
            $table->integer('score')->nullable(); // 0-100 or task-specific scale
            $table->text('limitations')->nullable();
            $table->text('assistive_devices_used')->nullable();
            $table->timestamp('assessed_at');
            $table->foreignId('therapist_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['episode_id', 'functional_task']);
        });

        // 15. Posture Analysis - Musculoskeletal
        Schema::create('posture_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('clinical_assessments')->onDelete('cascade');
            $table->foreignId('episode_id')->constrained('episodes_of_care')->onDelete('cascade');
            $table->enum('view', ['anterior', 'posterior', 'lateral', 'all'])->default('all');
            $table->json('deviations')->nullable(); // Forward head, rounded shoulders, etc.
            $table->text('alignment_notes')->nullable();
            $table->text('photo_references')->nullable(); // File paths to posture photos
            $table->timestamp('assessed_at');
            $table->foreignId('therapist_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // 16. Gait Analysis - Neurological/Musculoskeletal
        Schema::create('gait_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->constrained('clinical_assessments')->onDelete('cascade');
            $table->foreignId('episode_id')->constrained('episodes_of_care')->onDelete('cascade');
            $table->decimal('gait_speed', 5, 2)->nullable(); // m/s
            $table->integer('cadence')->nullable(); // steps per minute
            $table->decimal('step_length', 5, 2)->nullable(); // cm
            $table->decimal('stride_length', 5, 2)->nullable(); // cm
            $table->text('gait_pattern')->nullable(); // Normal, antalgic, Trendelenburg, etc.
            $table->text('deviations')->nullable(); // Limp, toe walking, etc.
            $table->text('assistive_devices')->nullable();
            $table->timestamp('assessed_at');
            $table->foreignId('therapist_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // 17. Vital Signs Log - Cardiopulmonary/General
        Schema::create('vital_signs_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assessment_id')->nullable()->constrained('clinical_assessments')->onDelete('cascade');
            $table->foreignId('episode_id')->constrained('episodes_of_care')->onDelete('cascade');
            $table->integer('heart_rate')->nullable();
            $table->integer('blood_pressure_systolic')->nullable();
            $table->integer('blood_pressure_diastolic')->nullable();
            $table->integer('respiratory_rate')->nullable();
            $table->integer('oxygen_saturation')->nullable();
            $table->decimal('temperature', 4, 1)->nullable(); // Celsius
            $table->text('notes')->nullable();
            $table->timestamp('recorded_at');
            $table->foreignId('therapist_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['episode_id', 'recorded_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vital_signs_logs');
        Schema::dropIfExists('gait_analyses');
        Schema::dropIfExists('posture_analyses');
        Schema::dropIfExists('functional_scores');
        Schema::dropIfExists('outcome_measures');
        Schema::dropIfExists('pain_management_assessments');
        Schema::dropIfExists('pelvic_floor_assessments');
        Schema::dropIfExists('sports_performance_assessments');
        Schema::dropIfExists('geriatric_assessments');
        Schema::dropIfExists('pediatric_assessments');
        Schema::dropIfExists('cardiopulmonary_assessments');
        Schema::dropIfExists('neurological_assessments');
        Schema::dropIfExists('pain_assessments');
        Schema::dropIfExists('special_orthopedic_tests');
        Schema::dropIfExists('muscle_strength_grades');
        Schema::dropIfExists('joint_rom_measurements');
        Schema::dropIfExists('pt_specialty_configs');
    }
};

