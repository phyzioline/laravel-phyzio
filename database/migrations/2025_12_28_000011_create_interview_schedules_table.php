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
        Schema::create('interview_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_application_id')->constrained()->onDelete('cascade');
            $table->foreignId('job_id')->constrained('clinic_jobs')->onDelete('cascade');
            $table->foreignId('therapist_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('company_id')->constrained('users')->onDelete('cascade');
            $table->datetime('scheduled_at');
            $table->string('interview_type')->default('online'); // online, in-person, phone
            $table->text('location')->nullable(); // For in-person interviews
            $table->text('meeting_link')->nullable(); // For online interviews
            $table->text('notes')->nullable();
            $table->string('status')->default('scheduled'); // scheduled, completed, cancelled, rescheduled
            $table->text('feedback')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interview_schedules');
    }
};

