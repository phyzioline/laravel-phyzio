<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('courses', function (Blueprint $table) {
            // Check if columns exist before adding them to avoid duplication issues if partial run
            if (!Schema::hasColumn('courses', 'specialty')) $table->string('specialty')->nullable()->after('type'); // ortho, neuro, etc.
            if (!Schema::hasColumn('courses', 'level')) $table->enum('level', ['student', 'junior', 'senior', 'consultant'])->default('junior')->after('specialty');
            if (!Schema::hasColumn('courses', 'total_hours')) $table->decimal('total_hours', 5, 2)->default(0)->after('level');
            if (!Schema::hasColumn('courses', 'practical_hours')) $table->decimal('practical_hours', 5, 2)->default(0)->after('total_hours');
            if (!Schema::hasColumn('courses', 'clinical_focus')) $table->text('clinical_focus')->nullable()->after('practical_hours'); // Description of clinical outcome
            if (!Schema::hasColumn('courses', 'equipment_required')) $table->json('equipment_required')->nullable()->after('clinical_focus');
            if (!Schema::hasColumn('courses', 'accreditation_status')) $table->string('accreditation_status')->nullable()->after('equipment_required');
            if (!Schema::hasColumn('courses', 'subscription_included')) $table->boolean('subscription_included')->default(false)->after('price');
        });
    }

    public function down()
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'specialty',
                'level',
                'total_hours',
                'practical_hours',
                'clinical_focus',
                'equipment_required',
                'accreditation_status',
                'subscription_included'
            ]);
        });
    }
};
