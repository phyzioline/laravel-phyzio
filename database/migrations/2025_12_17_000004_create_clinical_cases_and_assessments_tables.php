<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('clinical_cases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('pathology')->nullable();
            $table->string('icd_code')->nullable();
            $table->string('patient_age_group')->nullable(); // Pediatric, Adult, Geriatric
            $table->json('assessment_tools')->nullable(); // ['VAS', 'ROM']
            $table->text('red_flags')->nullable();
            $table->text('treatment_plan')->nullable();
            $table->json('outcome_measures')->nullable();
            $table->timestamps();
        });

        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('unit_id')->nullable()->constrained('course_units')->onDelete('cascade'); // Can be linked to a unit
            $table->foreignId('course_id')->nullable()->constrained()->onDelete('cascade'); // Or a whole course exam
            $table->enum('assessment_type', ['mcq', 'case_study', 'video_submission'])->default('mcq');
            $table->integer('pass_score')->default(70);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('assessments');
        Schema::dropIfExists('clinical_cases');
    }
};
