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
            // Drop foreign key constraint first
            $table->dropForeign(['patient_id']);
            
            // Make patient_id nullable for guest bookings
            $table->unsignedBigInteger('patient_id')->nullable()->change();
            
            // Add guest information fields
            $table->string('guest_name')->nullable()->after('patient_id');
            $table->string('guest_email')->nullable()->after('guest_name');
            $table->string('guest_phone')->nullable()->after('guest_email');
            $table->boolean('is_guest_booking')->default(false)->after('guest_phone');
            
            // Re-add foreign key constraint with nullable
            $table->foreign('patient_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_visits', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['patient_id']);
            
            // Remove guest fields
            $table->dropColumn(['guest_name', 'guest_email', 'guest_phone', 'is_guest_booking']);
            
            // Make patient_id required again
            $table->unsignedBigInteger('patient_id')->nullable(false)->change();
            
            // Re-add foreign key constraint
            $table->foreign('patient_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
};

