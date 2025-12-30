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
        Schema::create('clinic_staff', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('role', ['therapist', 'admin', 'receptionist', 'doctor'])->default('therapist');
            $table->boolean('is_active')->default(true);
            $table->date('hired_date')->nullable();
            $table->date('terminated_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Prevent duplicate assignments
            $table->unique(['clinic_id', 'user_id'], 'clinic_user_unique');
            
            // Indexes for performance
            $table->index('clinic_id');
            $table->index('user_id');
            $table->index(['clinic_id', 'is_active'], 'idx_clinic_active_staff');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_staff');
    }
};
