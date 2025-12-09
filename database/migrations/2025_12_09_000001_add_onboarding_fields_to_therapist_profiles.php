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
        Schema::table('therapist_profiles', function (Blueprint $table) {
            // Personal Info
            $table->string('national_id')->nullable()->after('user_id');
            $table->date('date_of_birth')->nullable()->after('national_id');
            $table->string('gender')->nullable()->after('date_of_birth');
            $table->string('profile_photo')->nullable()->after('gender');
            
            // Professional Details
            $table->string('professional_level')->nullable()->after('years_experience'); // Senior, Junior, Assistant
            $table->string('license_issuing_authority')->nullable()->after('license_number');
            $table->string('university_degree')->nullable()->after('license_issuing_authority');
            $table->json('additional_certificates')->nullable()->after('university_degree'); // Manual therapy, dry needling, etc.
            
            // Activity Settings
            $table->string('video_promo')->nullable()->after('bio');
            
            // Settings & Preferences
            $table->json('notification_preferences')->nullable()->after('working_hours');
            $table->enum('profile_visibility', ['public', 'assigned_only', 'hidden'])->default('public')->after('status');
            
            // Clinic/Work Preferences
            $table->integer('max_sessions_per_day')->default(8)->after('working_hours');
            $table->integer('break_hours')->default(1)->after('max_sessions_per_day');
            
            // Payment Extras
            $table->string('mobile_wallet_number')->nullable()->after('bank_account_name');
            $table->string('payment_frequency')->default('monthly')->after('mobile_wallet_number'); // Weekly, Bi-weekly, Monthly
            
            // Templates (JSON storage for flexibility)
            $table->json('evaluation_template')->nullable()->after('payment_frequency');
            $table->json('treatment_plan_template')->nullable()->after('evaluation_template');
            $table->json('session_note_template')->nullable()->after('treatment_plan_template');
            
            // Calendar Rules
            $table->json('calendar_settings')->nullable()->after('session_note_template'); // view, overlap, reminders, etc.
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('therapist_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'national_id', 'date_of_birth', 'gender', 'profile_photo',
                'professional_level', 'license_issuing_authority', 'university_degree', 'additional_certificates',
                'video_promo', 'notification_preferences', 'profile_visibility',
                'max_sessions_per_day', 'break_hours',
                'mobile_wallet_number', 'payment_frequency',
                'evaluation_template', 'treatment_plan_template', 'session_note_template',
                'calendar_settings'
            ]);
        });
    }
};
