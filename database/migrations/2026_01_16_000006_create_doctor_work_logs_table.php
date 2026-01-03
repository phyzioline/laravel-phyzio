<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_work_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('appointment_id')->nullable()->constrained('clinic_appointments')->onDelete('set null');
            $table->foreignId('slot_id')->nullable()->constrained('booking_slots')->onDelete('set null');
            $table->foreignId('assignment_id')->nullable()->constrained('slot_doctor_assignments')->onDelete('set null');
            
            // Work details
            $table->date('work_date');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->decimal('hours_worked', 5, 2)->default(0); // e.g., 1.5, 2.0, 0.5
            $table->integer('minutes_worked')->default(0);
            
            // Payment calculation
            $table->decimal('hourly_rate', 10, 2)->default(0);
            $table->decimal('total_amount', 10, 2)->default(0); // hours_worked * hourly_rate
            
            // Patient info (for reporting)
            $table->foreignId('patient_id')->nullable()->constrained('patients')->onDelete('set null');
            $table->string('session_type')->nullable(); // 'regular' or 'intensive'
            $table->string('specialty')->nullable();
            
            // Status
            $table->enum('status', ['pending', 'confirmed', 'paid', 'cancelled'])->default('pending');
            $table->date('payment_date')->nullable();
            
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Indexes for reporting
            $table->index(['clinic_id', 'doctor_id', 'work_date']);
            $table->index(['work_date', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_work_logs');
    }
};

