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
        Schema::create('session_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('appointment_id')->constrained('appointments')->onDelete('cascade');
            $table->text('subjective')->nullable(); // Patient feedback
            $table->text('objective')->nullable(); // Measurements
            $table->text('assessment')->nullable(); // Professional opinion
            $table->text('plan')->nullable(); // Next steps
            $table->text('treatment_provided')->nullable();
            $table->integer('pain_level_before')->nullable();
            $table->integer('pain_level_after')->nullable();
            $table->boolean('is_finalized')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_notes');
    }
};
