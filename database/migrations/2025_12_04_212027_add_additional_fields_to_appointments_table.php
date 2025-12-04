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
        Schema::table('appointments', function (Blueprint $table) {
            // Enhanced location fields
            $table->string('location_address')->nullable()->after('location');
            $table->decimal('location_lat', 10, 7)->nullable()->after('location_address');
            $table->decimal('location_lng', 10, 7)->nullable()->after('location_lat');
            
            // Session details
            $table->integer('duration_hours')->default(1)->after('appointment_time'); // 1, 2, 3 hours
            $table->date('appointment_date')->nullable()->after('appointment_time');
            
            // Payment fields
            $table->string('payment_status')->default('pending')->after('price'); // pending, paid, refunded
            $table->string('payment_method')->nullable()->after('payment_status'); // card, cash, wallet
            $table->string('payment_transaction_id')->nullable()->after('payment_method');
            
            // Additional notes
            $table->text('patient_notes')->nullable()->after('notes');
            $table->text('therapist_notes')->nullable()->after('patient_notes');
            $table->text('cancellation_reason')->nullable()->after('therapist_notes');
            
            // Timestamps
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn([
                'location_address', 'location_lat', 'location_lng',
                'duration_hours', 'appointment_date',
                'payment_status', 'payment_method', 'payment_transaction_id',
                'patient_notes', 'therapist_notes', 'cancellation_reason',
                'confirmed_at', 'completed_at', 'cancelled_at'
            ]);
        });
    }
};
