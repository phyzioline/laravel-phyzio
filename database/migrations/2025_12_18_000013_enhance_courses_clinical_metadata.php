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
            $table->string('clinical_focus')->nullable()->after('specialty');
            $table->json('equipment_required')->nullable()->after('clinical_focus');
            $table->decimal('practical_hours', 8, 2)->default(0)->after('duration_minutes');
            $table->decimal('total_hours', 8, 2)->default(0)->after('practical_hours');
            $table->string('accreditation_status')->nullable()->after('status');
            $table->boolean('subscription_included')->default(false)->after('price');
            $table->json('countries_supported')->nullable()->after('language');
            $table->json('regulatory_mapping')->nullable()->after('countries_supported');
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
