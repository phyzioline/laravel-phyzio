<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('therapist_schedules', function (Blueprint $table) {
            if (!Schema::hasColumn('therapist_schedules', 'slot_duration')) {
                $table->integer('slot_duration')->default(30)->after('end_time');
            }
            if (!Schema::hasColumn('therapist_schedules', 'break_duration')) {
                $table->integer('break_duration')->default(0)->after('slot_duration');
            }
            if (!Schema::hasColumn('therapist_schedules', 'start_date')) {
                $table->date('start_date')->nullable()->after('break_duration');
            }
            if (!Schema::hasColumn('therapist_schedules', 'end_date')) {
                $table->date('end_date')->nullable()->after('start_date');
            }
            // Ensure is_active exists too, though likely it does
            if (!Schema::hasColumn('therapist_schedules', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
        });
    }

    public function down(): void
    {
        Schema::table('therapist_schedules', function (Blueprint $table) {
            $table->dropColumn(['slot_duration', 'break_duration', 'start_date', 'end_date']);
        });
    }
};
