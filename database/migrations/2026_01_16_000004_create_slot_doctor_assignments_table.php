<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slot_doctor_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('slot_id')->constrained('booking_slots')->onDelete('cascade');
            $table->foreignId('appointment_id')->constrained('clinic_appointments')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
            
            // Assignment details
            $table->dateTime('assigned_at')->nullable();
            $table->foreignId('assigned_by')->nullable()->constrained('users')->onDelete('set null');
            
            // Status
            $table->enum('status', ['assigned', 'confirmed', 'in_progress', 'completed', 'cancelled'])->default('assigned');
            
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slot_doctor_assignments');
    }
};

