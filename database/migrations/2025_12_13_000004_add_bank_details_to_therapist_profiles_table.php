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
            if (!Schema::hasColumn('therapist_profiles', 'bank_name')) {
                $table->string('bank_name')->nullable();
            }
            if (!Schema::hasColumn('therapist_profiles', 'bank_account_name')) {
                $table->string('bank_account_name')->nullable();
            }
            if (!Schema::hasColumn('therapist_profiles', 'bank_account_number')) {
                $table->string('bank_account_number')->nullable();
            }
            if (!Schema::hasColumn('therapist_profiles', 'iban')) {
                $table->string('iban')->nullable();
            }
            if (!Schema::hasColumn('therapist_profiles', 'swift_code')) {
                $table->string('swift_code')->nullable();
            }
            
            if (!Schema::hasColumn('therapist_profiles', 'can_access_clinic')) {
                $table->boolean('can_access_clinic')->default(false);
            }
            if (!Schema::hasColumn('therapist_profiles', 'can_access_instructor')) {
                $table->boolean('can_access_instructor')->default(false);
            }
            
            if (!Schema::hasColumn('therapist_profiles', 'available_areas')) {
                $table->json('available_areas')->nullable();
            }
            if (!Schema::hasColumn('therapist_profiles', 'total_earnings')) {
                $table->decimal('total_earnings', 10, 2)->default(0);
            }
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
