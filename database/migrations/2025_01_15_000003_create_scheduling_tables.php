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
        Schema::create('waitlists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('doctor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('specialty')->nullable();
            $table->string('visit_type')->nullable(); // evaluation, followup, re_evaluation
            $table->enum('priority', ['low', 'normal', 'high', 'urgent'])->default('normal');
            $table->text('notes')->nullable();
            $table->date('preferred_start_date')->nullable();
            $table->date('preferred_end_date')->nullable();
            $table->json('preferred_times')->nullable(); // Array of preferred time slots
            $table->json('preferred_days')->nullable(); // Array of preferred days
            $table->enum('status', ['active', 'notified', 'booked', 'cancelled', 'expired'])->default('active');
            $table->timestamp('notified_at')->nullable();
            $table->timestamp('booked_at')->nullable();
            $table->timestamps();
            
            $table->index(['clinic_id', 'status']);
            $table->index(['patient_id', 'status']);
            $table->index(['doctor_id', 'status']);
        });

        Schema::create('calendar_syncs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Therapist/doctor
            $table->enum('provider', ['google', 'outlook', 'apple'])->default('google');
            $table->string('calendar_id'); // External calendar ID
            $table->string('access_token');
            $table->string('refresh_token')->nullable();
            $table->timestamp('token_expires_at')->nullable();
            $table->boolean('sync_enabled')->default(true);
            $table->enum('sync_direction', ['two_way', 'import', 'export'])->default('two_way');
            $table->timestamp('last_synced_at')->nullable();
            $table->json('sync_settings')->nullable(); // Custom sync preferences
            $table->timestamps();
            
            $table->unique(['user_id', 'provider']);
            $table->index(['clinic_id', 'sync_enabled']);
        });

        Schema::create('intake_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->json('form_fields'); // Dynamic form structure
            $table->boolean('is_active')->default(true);
            $table->boolean('is_required')->default(false); // Required before appointment
            $table->json('conditional_logic')->nullable(); // Show/hide fields based on answers
            $table->timestamps();
            
            $table->index(['clinic_id', 'is_active']);
        });

        Schema::create('intake_form_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intake_form_id')->constrained('intake_forms')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('appointment_id')->nullable()->constrained('clinic_appointments')->onDelete('set null');
            $table->json('responses'); // Form field responses
            $table->enum('status', ['draft', 'submitted', 'reviewed'])->default('draft');
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
            
            $table->index(['patient_id', 'appointment_id']);
            $table->index(['intake_form_id', 'status']);
        });

        Schema::create('appointment_reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained('clinic_appointments')->onDelete('cascade');
            $table->enum('reminder_type', ['email', 'sms', 'push', 'phone'])->default('email');
            $table->integer('minutes_before')->default(1440); // 24 hours default
            $table->enum('status', ['pending', 'sent', 'failed', 'cancelled'])->default('pending');
            $table->timestamp('scheduled_for')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamps();
            
            $table->index(['appointment_id', 'status']);
            $table->index(['scheduled_for', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointment_reminders');
        Schema::dropIfExists('intake_form_responses');
        Schema::dropIfExists('intake_forms');
        Schema::dropIfExists('calendar_syncs');
        Schema::dropIfExists('waitlists');
    }
};

