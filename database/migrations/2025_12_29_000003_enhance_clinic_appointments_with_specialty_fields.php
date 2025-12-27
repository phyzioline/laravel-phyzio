<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Adds specialty-specific fields to appointments for enhanced reservation system.
     */
    public function up(): void
    {
        Schema::table('clinic_appointments', function (Blueprint $table) {
            // Common fields for all specialties
            $table->enum('visit_type', ['evaluation', 'followup', 're_evaluation'])->nullable()->after('duration_minutes');
            $table->enum('location', ['clinic', 'home'])->default('clinic')->after('visit_type');
            $table->enum('payment_method', ['cash', 'card', 'insurance'])->nullable()->after('location');
            
            // Specialty field for quick filtering and context
            $table->string('specialty')->nullable()->after('payment_method');
            
            // Session type for treatment categorization
            $table->string('session_type')->nullable()->after('specialty'); // manual_therapy, exercise, modality, combined, etc.
            
            // Indexes for performance
            $table->index('visit_type');
            $table->index('location');
            $table->index('specialty');
            $table->index('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clinic_appointments', function (Blueprint $table) {
            $table->dropIndex(['visit_type']);
            $table->dropIndex(['location']);
            $table->dropIndex(['specialty']);
            $table->dropIndex(['payment_method']);
            
            $table->dropColumn([
                'visit_type',
                'location',
                'payment_method',
                'specialty',
                'session_type'
            ]);
        });
    }
};

