<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * This table supports multi-specialty clinics.
     * A clinic can have multiple specialties activated.
     */
    public function up(): void
    {
        Schema::create('clinic_specialties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
            $table->string('specialty'); // orthopedic, pediatric, neurological, sports, geriatric, womens_health, cardiorespiratory, home_care
            $table->boolean('is_primary')->default(false); // Mark the primary specialty
            $table->boolean('is_active')->default(true); // Can deactivate without removing
            $table->timestamp('activated_at')->nullable();
            $table->timestamps();

            // Ensure a clinic can't have duplicate specialties
            $table->unique(['clinic_id', 'specialty']);
            
            // Index for quick lookups
            $table->index('clinic_id');
            $table->index('specialty');
            $table->index('is_primary');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_specialties');
    }
};

