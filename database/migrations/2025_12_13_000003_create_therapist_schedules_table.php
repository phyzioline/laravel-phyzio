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
        if (!Schema::hasTable('therapist_schedules')) {
            Schema::create('therapist_schedules', function (Blueprint $table) {
                $table->id();
                $table->foreignId('therapist_id')->constrained('users')->onDelete('cascade');
                $table->string('day_of_week')->comment('monday, tuesday, etc');
                $table->time('start_time');
                $table->time('end_time');
                $table->integer('slot_duration')->default(30)->comment('minutes');
                $table->integer('break_duration')->default(10)->comment('minutes');
                $table->date('start_date')->nullable(); // For range validity
                $table->date('end_date')->nullable();
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
        Schema::dropIfExists('therapist_schedules');
    }
};
