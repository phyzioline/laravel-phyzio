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
        Schema::table('home_visits', function (Blueprint $table) {
            $table->decimal('duration_hours', 8, 2)->nullable()->after('completed_at');
            $table->text('notes')->nullable()->after('duration_hours');
            $table->text('patient_notes')->nullable()->after('notes');
            $table->text('therapist_notes')->nullable()->after('patient_notes');
            $table->text('cancellation_reason')->nullable()->after('therapist_notes');
            $table->string('payment_transaction_id')->nullable()->after('payment_status');
            $table->timestamp('confirmed_at')->nullable()->after('scheduled_at');
            $table->timestamp('cancelled_at')->nullable()->after('completed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_visits', function (Blueprint $table) {
            $table->dropColumn([
                'duration_hours',
                'notes',
                'patient_notes',
                'therapist_notes',
                'cancellation_reason',
                'payment_transaction_id',
                'confirmed_at',
                'cancelled_at'
            ]);
        });
    }
};
