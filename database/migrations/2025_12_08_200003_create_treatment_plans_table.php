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
        Schema::create('treatment_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('therapist_id')->constrained('therapist_profiles'); // Assuming linking to profile or user
            $table->foreignId('patient_id')->constrained('users'); // Linking to patient user
            $table->string('diagnosis');
            $table->text('short_term_goals')->nullable();
            $table->text('long_term_goals')->nullable();
            $table->integer('planned_sessions');
            $table->string('frequency'); // e.g. "2x per week"
            $table->enum('status', ['active', 'completed', 'cancelled'])->default('active');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment_plans');
    }
};
