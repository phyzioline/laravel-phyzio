<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('doctor_specialty_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('users')->onDelete('cascade');
            $table->string('specialty'); // pediatric, orthopedic, etc.
            $table->boolean('is_head')->default(false); // Head of department
            $table->integer('priority')->default(0); // For ordering/display
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            // Unique: one doctor can only be assigned once per specialty per clinic
            $table->unique(['clinic_id', 'doctor_id', 'specialty'], 'unique_doctor_specialty');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('doctor_specialty_assignments');
    }
};

