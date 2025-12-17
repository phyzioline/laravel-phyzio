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
        Schema::table('courses', function (Blueprint $table) {
            if (!Schema::hasColumn('courses', 'specialty')) {
                $table->string('specialty')->nullable()->after('title');
            }
            if (!Schema::hasColumn('courses', 'clinical_focus')) {
                $table->string('clinical_focus')->nullable()->after('specialty');
            }
            if (!Schema::hasColumn('courses', 'equipment_required')) {
                $table->json('equipment_required')->nullable()->after('clinical_focus');
            }
            if (!Schema::hasColumn('courses', 'practical_hours')) {
                $table->decimal('practical_hours', 8, 2)->default(0)->after('duration_minutes');
            }
            if (!Schema::hasColumn('courses', 'total_hours')) {
                $table->decimal('total_hours', 8, 2)->default(0)->after('practical_hours');
            }
            if (!Schema::hasColumn('courses', 'accreditation_status')) {
                $table->string('accreditation_status')->nullable()->after('status');
            }
            if (!Schema::hasColumn('courses', 'subscription_included')) {
                $table->boolean('subscription_included')->default(false)->after('price');
            }

            // Ensure language exists, or add it if missing
            if (!Schema::hasColumn('courses', 'language')) {
                 $table->string('language')->default('English')->after('level');
            }

            if (!Schema::hasColumn('courses', 'countries_supported')) {
                // Position after language (now guaranteed to exist or we use level/status fallback)
                $afterColumn = Schema::hasColumn('courses', 'language') ? 'language' : 'status';
                $table->json('countries_supported')->nullable()->after($afterColumn);
            }
            if (!Schema::hasColumn('courses', 'regulatory_mapping')) {
                $table->json('regulatory_mapping')->nullable()->after('countries_supported');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'specialty',
                'clinical_focus',
                'equipment_required',
                'practical_hours',
                'total_hours',
                'accreditation_status',
                'subscription_included',
                'countries_supported',
                'regulatory_mapping'
            ]);
        });
    }
};
