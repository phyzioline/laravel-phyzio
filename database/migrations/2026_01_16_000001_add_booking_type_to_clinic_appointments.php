<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('clinic_appointments', function (Blueprint $table) {
            // Add booking type: 'regular' or 'intensive'
            $table->enum('booking_type', ['regular', 'intensive'])->default('regular')->after('session_type');
            
            // For intensive sessions: total hours (1-4)
            $table->integer('total_hours')->nullable()->after('booking_type');
            
            // Treatment plan reference (if part of a plan)
            $table->foreignId('treatment_plan_id')->nullable()->after('total_hours')
                ->constrained('treatment_plans')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('clinic_appointments', function (Blueprint $table) {
            $table->dropForeign(['treatment_plan_id']);
            $table->dropColumn(['booking_type', 'total_hours', 'treatment_plan_id']);
        });
    }
};

