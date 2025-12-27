<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Stores pricing configuration for each clinic and specialty.
     * Allows clinics to customize their pricing structure.
     */
    public function up(): void
    {
        Schema::create('clinic_pricing_configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
            $table->string('specialty'); // orthopedic, pediatric, etc.
            
            // Base Pricing
            $table->decimal('base_price', 10, 2)->default(0); // Base session price
            $table->decimal('evaluation_price', 10, 2)->nullable(); // Initial evaluation price
            $table->decimal('followup_price', 10, 2)->nullable(); // Follow-up session price
            $table->decimal('re_evaluation_price', 10, 2)->nullable(); // Re-evaluation price
            
            // Specialty Adjustment Coefficient (multiplier)
            $table->decimal('specialty_adjustment', 5, 2)->default(1.00); // 1.0 = no adjustment, 1.2 = 20% increase
            
            // Therapist Level Multipliers (JSON)
            // Example: {"junior": 1.0, "senior": 1.3, "consultant": 1.5}
            $table->json('therapist_level_multipliers')->nullable();
            
            // Equipment Pricing (JSON)
            // Example: {"shockwave": 50, "biofeedback": 30, "ultrasound": 25}
            $table->json('equipment_pricing')->nullable();
            
            // Location Factors (JSON)
            // Example: {"clinic": 1.0, "home_base": 1.2, "home_premium": 1.5}
            $table->json('location_factors')->nullable();
            
            // Duration Factors (JSON)
            // Example: {"30": 0.7, "45": 0.85, "60": 1.0, "90": 1.4}
            $table->json('duration_factors')->nullable();
            
            // Discount Rules (JSON)
            // Example: {"weekly_program": 10, "monthly_package": 20, "insurance": 15}
            $table->json('discount_rules')->nullable();
            
            // Insurance Settings
            $table->boolean('insurance_enabled')->default(false);
            $table->json('insurance_agreements')->nullable(); // Insurance company agreements
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // One config per clinic per specialty
            $table->unique(['clinic_id', 'specialty']);
            $table->index('clinic_id');
            $table->index('specialty');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_pricing_configs');
    }
};

