<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Stores specialty-specific additional data for appointments.
     * Uses JSON for flexibility across different specialties.
     */
    public function up(): void
    {
        Schema::create('reservation_additional_data', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained('clinic_appointments')->onDelete('cascade');
            $table->string('specialty'); // orthopedic, pediatric, etc.
            
            // JSON field to store all specialty-specific fields
            // Structure varies by specialty:
            // Orthopedic: {body_region, diagnosis, pain_level, equipment, intensity, etc.}
            // Pediatric: {child_age_months, guardian_attending, behavioral_notes, tolerance_level, etc.}
            // Neurological: {diagnosis, affected_side, mobility_level, cognitive_status, caregiver_present, etc.}
            // Sports: {sport_type, injury_phase, competition_date, training_load, clearance_level, etc.}
            // Geriatric: {fall_risk_level, assistive_device, chronic_conditions, family_contact, etc.}
            // Women's Health: {pregnancy_status, trimester, pain_sensitivity, privacy_level, etc.}
            // Home Care: {patient_address, travel_time, home_environment_notes, portable_equipment, etc.}
            $table->json('data')->nullable();
            
            $table->timestamps();

            // One-to-one relationship with appointment
            $table->unique('appointment_id');
            $table->index('specialty');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservation_additional_data');
    }
};

