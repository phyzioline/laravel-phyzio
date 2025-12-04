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
            // Professional Details
            $table->integer('years_experience')->nullable()->after('specialization');
            $table->json('available_areas')->nullable()->after('home_visit_rate'); // Array of area names/IDs
            $table->json('working_hours')->nullable()->after('available_areas'); // JSON structure for weekly schedule
            
            // Documents & Verification
            $table->string('license_document')->nullable()->after('license_number');
            $table->string('id_document')->nullable()->after('license_document');
            $table->timestamp('verified_at')->nullable()->after('status');
            
            // Stats
            $table->integer('total_reviews')->default(0)->after('rating');
            $table->decimal('total_earnings', 12, 2)->default(0)->after('total_reviews');
            $table->decimal('platform_balance', 12, 2)->default(0)->after('total_earnings');
            
            // Bank Details for Withdrawal
            $table->string('bank_name')->nullable()->after('platform_balance');
            $table->string('bank_account_number')->nullable()->after('bank_name');
            $table->string('bank_account_name')->nullable()->after('bank_account_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('therapist_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'years_experience', 'available_areas', 'working_hours',
                'license_document', 'id_document', 'verified_at',
                'total_reviews', 'total_earnings', 'platform_balance',
                'bank_name', 'bank_account_number', 'bank_account_name'
            ]);
        });
    }
};
