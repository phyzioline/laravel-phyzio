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
        Schema::table('clinics', function (Blueprint $table) {
            if (!Schema::hasColumn('clinics', 'onboarding_completed')) {
                $table->boolean('onboarding_completed')->default(false)->after('specialty_selected_at');
            }
            if (!Schema::hasColumn('clinics', 'onboarding_completed_at')) {
                $table->timestamp('onboarding_completed_at')->nullable()->after('onboarding_completed');
            }
            if (!Schema::hasColumn('clinics', 'onboarding_completed_steps')) {
                $table->json('onboarding_completed_steps')->nullable()->after('onboarding_completed_at');
            }
            if (!Schema::hasColumn('clinics', 'onboarding_skipped')) {
                $table->boolean('onboarding_skipped')->default(false)->after('onboarding_completed_steps');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->dropColumn([
                'onboarding_completed',
                'onboarding_completed_at',
                'onboarding_completed_steps',
                'onboarding_skipped'
            ]);
        });
    }
};

