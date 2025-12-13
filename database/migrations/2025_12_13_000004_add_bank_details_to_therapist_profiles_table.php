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
            $table->string('bank_name')->nullable();
            $table->string('bank_account_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('iban')->nullable();
            $table->string('swift_code')->nullable();
            
            // Access control flags if not already present on users table
            $table->boolean('can_access_clinic')->default(false);
            $table->boolean('can_access_instructor')->default(false);
            
            // Other missing fields from model
            $table->json('available_areas')->nullable();
            $table->decimal('total_earnings', 10, 2)->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('therapist_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'bank_name', 
                'bank_account_name', 
                'bank_account_number', 
                'iban', 
                'swift_code',
                'can_access_clinic',
                'can_access_instructor',
                'available_areas',
                'total_earnings'
            ]);
        });
    }
};
