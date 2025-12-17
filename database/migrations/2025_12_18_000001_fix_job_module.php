<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Rename conflicting 'jobs' table (if it exists and is OURS) to 'clinic_jobs'
        // If 'jobs' exists, we need to check if it's the queue table or ours. 
        // Our table has 'clinic_id', default queue table doesn't.
        if (Schema::hasTable('jobs')) {
            if (Schema::hasColumn('jobs', 'clinic_id')) {
                Schema::rename('jobs', 'clinic_jobs');
            }
        }

        // 2. Create 'clinic_jobs' if it doesn't exist
        if (!Schema::hasTable('clinic_jobs')) {
            Schema::create('clinic_jobs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('clinic_id')->constrained('users')->onDelete('cascade');
                $table->string('title');
                $table->text('description');
                $table->string('type')->default('job'); // job, training
                $table->string('location')->nullable();
                $table->string('salary_range')->nullable();
                $table->string('file_path')->nullable();
                $table->boolean('is_active')->default(true);
                
                // Enhanced fields
                $table->json('specialty')->nullable();
                $table->json('techniques')->nullable();
                $table->json('equipment')->nullable();
                $table->string('experience_level')->nullable();
                $table->string('urgency_level')->nullable();
                $table->string('salary_type')->nullable(); // hourly, monthly
                $table->json('benefits')->nullable();
                $table->integer('openings_count')->default(1);
                $table->boolean('featured')->default(false);
                $table->string('posted_by_type')->default('clinic'); // clinic, admin

                $table->timestamps();
            });
        }

        // 3. Job Requirements
        if (!Schema::hasTable('job_requirements')) {
            Schema::create('job_requirements', function (Blueprint $table) {
                $table->id();
                $table->foreignId('job_id')->constrained('clinic_jobs')->onDelete('cascade');
                $table->text('requirement');
                $table->timestamps();
            });
        }

        // 4. Job Applications
         if (!Schema::hasTable('job_applications')) {
            Schema::create('job_applications', function (Blueprint $table) {
                $table->id();
                $table->foreignId('job_id')->constrained('clinic_jobs')->onDelete('cascade');
                $table->foreignId('applicant_id')->constrained('users')->onDelete('cascade');
                $table->string('status')->default('pending'); // pending, rejected, interviewed, hired
                $table->text('cover_letter')->nullable();
                $table->string('resume_path')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
        Schema::dropIfExists('job_requirements');
        Schema::dropIfExists('clinic_jobs');
    }
};
