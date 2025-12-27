<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Stores individual sessions within a weekly program.
     * Links to appointments when booked.
     */
    public function up(): void
    {
        Schema::create('program_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained('weekly_programs')->onDelete('cascade');
            $table->foreignId('appointment_id')->nullable()->constrained('clinic_appointments')->onDelete('set null');
            
            // Session Details
            $table->date('scheduled_date');
            $table->time('scheduled_time')->nullable();
            $table->integer('week_number'); // Week 1, 2, 3, etc.
            $table->integer('session_number'); // Session 1, 2, 3 within the week
            $table->integer('session_in_program'); // Overall session number (1, 2, 3... total)
            
            // Session Type
            $table->enum('session_type', ['evaluation', 'followup', 're_evaluation', 'discharge'])->default('followup');
            
            // Status
            $table->enum('status', ['scheduled', 'booked', 'completed', 'cancelled', 'no_show', 'rescheduled'])->default('scheduled');
            
            // Completion Data
            $table->timestamp('attended_at')->nullable();
            $table->text('session_notes')->nullable();
            $table->text('therapist_notes')->nullable();
            
            // Rescheduling
            $table->foreignId('rescheduled_from_id')->nullable()->constrained('program_sessions')->onDelete('set null');
            $table->date('original_date')->nullable();
            
            // Pricing (locked at program creation)
            $table->decimal('session_price', 10, 2)->nullable();
            
            $table->timestamps();

            $table->index('program_id');
            $table->index('appointment_id');
            $table->index('scheduled_date');
            $table->index('status');
            $table->index(['program_id', 'week_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_sessions');
    }
};

