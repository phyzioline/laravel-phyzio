<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Visit Packages (e.g., 10 Sessions Post-Op)
        Schema::create('visit_packages', function (Blueprint $table) {
            $table->id();
            $table->string('name_en');
            $table->string('name_ar')->nullable();
            $table->decimal('price', 10, 2);
            $table->integer('session_count')->default(1);
            $table->text('description')->nullable();
            $table->string('condition_type')->nullable(); // ortho, neuro, etc.
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // 2. Home Visits (The Core Transaction)
        Schema::create('home_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->nullable()->constrained('users')->onDelete('cascade'); // User requesting
            $table->foreignId('therapist_id')->nullable()->constrained('users')->onDelete('set null'); // Assigned therapist
            $table->foreignId('package_id')->nullable()->constrained('visit_packages');
            
            // Location Data
            $table->decimal('location_lat', 10, 8);
            $table->decimal('location_lng', 11, 8);
            $table->string('address');
            $table->string('city')->nullable();
            
            // Scheduling
            $table->dateTime('scheduled_at');
            $table->dateTime('arrived_at')->nullable();
            $table->dateTime('completed_at')->nullable();
            
            // Status Flow
            $table->enum('status', [
                'requested',    // Patient made request
                'accepted',     // Therapist accepted
                'on_way',       // Therapist moving
                'in_session',   // Checked in
                'completed',    // Done
                'cancelled',    // By patient or therapist
                'no_show'       // Therapist arrived but patient missing
            ])->default('requested');
            
            // Clinical Context
            $table->string('complain_type')->nullable(); // Pain, Post-op, etc.
            $table->enum('urgency', ['normal', 'urgent'])->default('normal');
            
            // Payment
            $table->decimal('total_amount', 10, 2);
            $table->enum('payment_method', ['cash', 'card', 'wallet', 'insurance'])->default('cash');
            $table->string('payment_status')->default('pending'); // pending, paid, refunded
            
            $table->timestamps();
        });

        // 3. Clinical Notes (Mandatory for "Professional" Flow)
        Schema::create('visit_clinical_notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('home_visit_id')->constrained()->onDelete('cascade');
            $table->text('chief_complaint');
            $table->json('assessment_findings')->nullable(); // SOAP: S & O
            $table->json('treatment_performed')->nullable(); // SOAP: A & P
            $table->text('outcome_measures')->nullable(); // VAS score, etc.
            $table->text('next_plan')->nullable();
            $table->json('vital_signs')->nullable(); // BP, HR, O2
            $table->timestamps();
        });

        // 4. Geo Status (For Uber-like Tracking)
        Schema::create('therapist_geo_status', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->boolean('is_online')->default(false);
            $table->decimal('current_lat', 10, 8)->nullable();
            $table->decimal('current_lng', 11, 8)->nullable();
            $table->timestamp('last_updated_at')->nullable();
            $table->string('current_visit_id')->nullable(); // If currently in active visit
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('therapist_geo_status');
        Schema::dropIfExists('visit_clinical_notes');
        Schema::dropIfExists('home_visits');
        Schema::dropIfExists('visit_packages');
    }
};
