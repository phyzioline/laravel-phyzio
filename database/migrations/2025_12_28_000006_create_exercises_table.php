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
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('video_url')->nullable();
            $table->string('image_url')->nullable();
            
            $table->enum('difficulty_level', ['Beginner', 'Intermediate', 'Advanced'])->default('Beginner');
            $table->string('target_muscle_group')->nullable();
            $table->json('equipment_needed')->nullable();
            $table->json('instructions')->nullable(); // Step-by-step instructions
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
