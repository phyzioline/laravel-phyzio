<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Stores weekly treatment programs for patients.
     * Programs include structured session plans with progression rules.
     */
    public function up(): void
    {
        Schema::create('weekly_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');
            $table->foreignId('episode_id')->nullable()->constrained('episodes_of_care')->onDelete('set null');
            $table->foreignId('therapist_id')->nullable()->constrained('users')->onDelete('set null');
            
            // Program Details
            $table->string('program_name'); // e.g., "Post-ACL Reconstruction - 12 Weeks"
            $table->string('specialty'); // orthopedic, pediatric, etc.
            
            // Session Configuration
            $table->integer('sessions_per_week'); // 1, 2, 3, 4, 5
            $table->integer('total_weeks'); // Total program duration
            $table->integer('total_sessions'); // Calculated: sessions_per_week * total_weeks
            
            // Session Types (JSON)
            // Example: {"week_1_2": ["evaluation", "followup"], "week_3_4": ["followup", "re_evaluation"]}
            $table->json('session_types')->nullable();
            
            // Progression Rules (JSON)
            // Example: {"week_1_2": {"intensity": "low", "focus": "pain_management"}, "week_3_4": {"intensity": "moderate", "focus": "strength"}}
            $table->json('progression_rules')->nullable();
            
            // Re-assessment Schedule
            $table->integer('reassessment_interval_weeks')->default(4); // Every 4 weeks
            $table->json('reassessment_schedule')->nullable(); // Specific weeks for reassessment
            
            // Payment Plan
            $table->enum('payment_plan', ['pay_per_week', 'monthly', 'upfront'])->default('pay_per_week');
            $table->decimal('total_price', 10, 2); // Total program price
            $table->decimal('discount_percentage', 5, 2)->default(0); // Discount applied
            $table->decimal('weekly_price', 10, 2)->nullable(); // Price per week
            $table->decimal('monthly_price', 10, 2)->nullable(); // Price per month
            $table->decimal('paid_amount', 10, 2)->default(0); // Amount paid so far
            $table->decimal('remaining_balance', 10, 2); // Remaining balance
            
            // Status
            $table->enum('status', ['draft', 'active', 'completed', 'cancelled', 'paused'])->default('draft');
            
            // Dates
            $table->date('start_date');
            $table->date('end_date')->nullable(); // Calculated or manual
            $table->date('actual_end_date')->nullable(); // Actual completion date
            
            // Auto-booking Settings
            $table->boolean('auto_booking_enabled')->default(true);
            $table->json('preferred_times')->nullable(); // Preferred appointment times
            $table->json('preferred_days')->nullable(); // Preferred days of week
            
            // Notes
            $table->text('notes')->nullable();
            $table->text('goals')->nullable(); // Program goals
            
            $table->timestamps();

            $table->index('clinic_id');
            $table->index('patient_id');
            $table->index('specialty');
            $table->index('status');
            $table->index('start_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_programs');
    }
};

