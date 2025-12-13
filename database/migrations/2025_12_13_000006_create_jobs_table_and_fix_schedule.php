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
        // Fix for Therapist Schedules
        if (Schema::hasTable('therapist_schedules')) {
            Schema::table('therapist_schedules', function (Blueprint $table) {
                if (!Schema::hasColumn('therapist_schedules', 'therapist_id')) {
                    $table->foreignId('therapist_id')->nullable()->constrained('users')->onDelete('cascade');
                }
            });
        }

        // Create Jobs Table
        if (!Schema::hasTable('jobs')) {
            Schema::create('jobs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('clinic_id')->constrained('users')->onDelete('cascade'); // The clinic posting the job
                $table->string('title');
                $table->text('description');
                $table->string('type')->default('job'); // job, training
                $table->string('location')->nullable();
                $table->string('salary_range')->nullable();
                $table->string('file_path')->nullable(); // For detailed PDF/Job Description
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jobs');
        
        if (Schema::hasTable('therapist_schedules') && Schema::hasColumn('therapist_schedules', 'therapist_id')) {
             // We generally don't drop columns in down() to avoid data loss during rollbacks of unrelated things
        }
    }
};
