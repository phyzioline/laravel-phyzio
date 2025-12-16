<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('skills', function (Blueprint $table) {
            $table->id();
            $table->string('skill_name');
            $table->string('specialty')->nullable(); // e.g., MSK, Neuro
            $table->string('body_region')->nullable(); // e.g., Cervical Spine, Knee
            $table->text('indications')->nullable();
            $table->text('contraindications')->nullable();
            $table->string('risk_level')->default('low'); // low, medium, high
            $table->timestamps();
        });

        Schema::create('course_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->foreignId('skill_id')->constrained()->onDelete('cascade');
            $table->enum('mastery_level_required', ['basic', 'advanced'])->default('basic');
            $table->timestamps();
        });

        Schema::create('skill_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Therapist
            $table->foreignId('skill_id')->constrained()->onDelete('cascade');
            $table->string('verification_type')->default('instructor'); // instructor, video_ai, peer
            $table->integer('score')->nullable(); // 0-100
            $table->boolean('verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('verified_by')->nullable()->constrained('users'); // Instructor ID
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('skill_verifications');
        Schema::dropIfExists('course_skills');
        Schema::dropIfExists('skills');
    }
};
