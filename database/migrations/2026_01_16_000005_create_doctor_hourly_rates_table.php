<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_hourly_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            
            // Rate configuration
            $table->decimal('hourly_rate', 10, 2)->default(0);
            $table->enum('rate_type', ['fixed', 'variable'])->default('fixed');
            $table->json('variable_rates')->nullable(); // For different specialties or time slots
            
            // Effective dates
            $table->date('effective_from')->nullable();
            $table->date('effective_to')->nullable();
            
            // Limits
            $table->integer('max_hours_per_day')->default(8);
            $table->integer('max_hours_per_week')->default(40);
            
            // Allowed specialties
            $table->json('allowed_specialties')->nullable();
            
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Unique constraint: one active rate per doctor per clinic
            $table->unique(['clinic_id', 'doctor_id', 'is_active'], 'unique_active_rate');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_hourly_rates');
    }
};

