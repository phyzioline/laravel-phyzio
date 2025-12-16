<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Enhance Users Table (Patient Details)
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'guardian_info')) $table->json('guardian_info')->nullable()->after('address'); // For pediatrics
            if (!Schema::hasColumn('users', 'emergency_contact')) $table->json('emergency_contact')->nullable()->after('guardian_info');
            if (!Schema::hasColumn('users', 'medical_history')) $table->text('medical_history')->nullable()->after('emergency_contact');
            if (!Schema::hasColumn('users', 'allergies')) $table->text('allergies')->nullable()->after('medical_history');
            if (!Schema::hasColumn('users', 'insurance_info')) $table->json('insurance_info')->nullable()->after('allergies');
        });

        // 2. Episodes of Care (The Container)
        Schema::create('episodes_of_care', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('clinic_id')->nullable()->constrained('users')->onDelete('cascade'); // Clinic Owner
            $table->foreignId('primary_therapist_id')->nullable()->constrained('users')->onDelete('set null');
            
            $table->enum('specialty', ['orthopedic', 'pediatric', 'neurological', 'sports', 'nutrition', 'geriatric', 'cardiopulmonary']);
            $table->string('diagnosis_icd')->nullable();
            $table->text('chief_complaint')->nullable();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ['active', 'paused', 'completed', 'discharged'])->default('active');
            $table->text('discharge_submmary')->nullable();
            
            $table->timestamps();
        });

        // 3. Clinical Assessments (The Brain)
        Schema::create('clinical_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('episode_id')->constrained('episodes_of_care')->onDelete('cascade');
            $table->foreignId('therapist_id')->constrained('users');
            $table->date('assessment_date');
            
            $table->enum('type', ['initial', 're_eval', 'discharge', 'daily']);
            
            // JSON Fields for Specialty Flexibility
            // Ortho: { rom: { knee_flex: 120 }, pain: 5, special_tests: { lachman: 'positive' } }
            // Neuro: { reflexes: [], muscle_tone: 2, balance_berg: 45 }
            // Peds: { milestones: { sitting: true }, gmfm_score: 88 }
            $table->json('subjective_data')->nullable(); // Patient says
            $table->json('objective_data')->nullable();  // Measurements
            
            $table->text('analysis')->nullable(); // Clinical reasoning
            $table->boolean('red_flags_detected')->default(false);
            $table->json('attachments')->nullable(); // X-Rays, Videos
            
            $table->timestamps();
        });

        // 4. Treatments (The Action)
        Schema::create('treatments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('episode_id')->constrained('episodes_of_care')->onDelete('cascade');
            $table->foreignId('assessment_id')->nullable()->constrained('clinical_assessments');
            $table->foreignId('therapist_id')->constrained('users');
            $table->date('treatment_date');
            
            $table->string('category'); // Manual Therapy, Exercise, Modalities, Neuro, Peds Play
            
            // JSON for detailed logging
            // { technique: 'Maitland G3', duration: 15, area: 'Cervical' }
            // { exercise: 'Squats', sets: 3, reps: 10, load: '10kg' }
            $table->json('details'); 
            
            $table->integer('duration_minutes')->default(0);
            $table->string('patient_response')->nullable(); // Tolerated well, Pain increased
            
            $table->timestamps();
        });

        // 5. Outcomes (The Proof)
        Schema::create('outcomes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('episode_id')->constrained('episodes_of_care')->onDelete('cascade');
            $table->date('date');
            $table->string('metric_name'); // e.g., 'Knee Flexion', 'Pain Level', 'Walking Speed'
            
            $table->decimal('value_numeric', 8, 2)->nullable(); // 120.00
            $table->string('value_text')->nullable(); // 'Independent'
            
            $table->string('unit')->nullable(); // 'degrees', '/10', 'm/s'
            $table->string('context')->nullable(); // 'Active', 'Passive'
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('outcomes');
        Schema::dropIfExists('treatments');
        Schema::dropIfExists('clinical_assessments');
        Schema::dropIfExists('episodes_of_care');
        
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['guardian_info', 'emergency_contact', 'medical_history', 'allergies', 'insurance_info']);
        });
    }
};
