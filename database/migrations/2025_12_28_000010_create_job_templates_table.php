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
        Schema::create('job_templates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Company user ID
            $table->string('name');
            $table->string('title');
            $table->text('description');
            $table->string('type')->default('job'); // job or training
            $table->string('location')->nullable();
            $table->string('salary_type')->nullable();
            $table->string('salary_range')->nullable();
            $table->json('specialty')->nullable();
            $table->json('techniques')->nullable();
            $table->json('equipment')->nullable();
            $table->string('experience_level')->nullable();
            $table->string('urgency_level')->nullable();
            $table->integer('openings_count')->default(1);
            $table->integer('min_years_experience')->nullable();
            $table->string('gender_preference')->nullable();
            $table->boolean('license_required')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_templates');
    }
};

