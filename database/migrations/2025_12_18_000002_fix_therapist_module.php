<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Therapist Schedules
        if (!Schema::hasTable('therapist_schedules')) {
            Schema::create('therapist_schedules', function (Blueprint $table) {
                $table->id();
                $table->foreignId('therapist_id')->constrained('users')->onDelete('cascade');
                $table->string('day_of_week')->comment('monday, tuesday, etc');
                $table->time('start_time');
                $table->time('end_time');
                $table->integer('slot_duration')->default(30);
                $table->integer('break_duration')->default(10);
                $table->date('start_date')->nullable();
                $table->date('end_date')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
            });
        }

        // 2. Therapist Services (Specialties/Types)
        if (!Schema::hasTable('therapist_services')) {
             Schema::create('therapist_services', function (Blueprint $table) {
                $table->id();
                $table->foreignId('therapist_id')->constrained('users')->onDelete('cascade');
                $table->string('name'); // e.g. "Massage", "Consultation"
                $table->decimal('price', 10, 2);
                $table->integer('duration_minutes');
                $table->timestamps();
            });
        }

        // 3. Home Visit Settings (if used)
        if (!Schema::hasTable('home_visit_settings')) {
            Schema::create('home_visit_settings', function (Blueprint $table) {
                $table->id();
                $table->foreignId('therapist_id')->constrained('users')->onDelete('cascade');
                $table->boolean('is_enabled')->default(false);
                $table->decimal('base_rate', 10, 2)->nullable();
                $table->integer('max_distance_km')->default(20);
                $table->json('covered_areas')->nullable(); // List of zones/zipcodes
                $table->timestamps();
            });
        }
        
        // 4. Clinical Certificates (if missing)
         if (!Schema::hasTable('certificates')) {
            Schema::create('certificates', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Can be therapist or clinic staff
                $table->string('title');
                $table->string('issuing_organization');
                $table->date('issue_date');
                $table->date('expiry_date')->nullable();
                $table->string('credential_id')->nullable();
                $table->string('file_path')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('certificates');
        Schema::dropIfExists('home_visit_settings');
        Schema::dropIfExists('therapist_services');
        Schema::dropIfExists('therapist_schedules');
    }
};
