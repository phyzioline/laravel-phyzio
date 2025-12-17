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
        Schema::table('skills', function (Blueprint $table) {
            $table->string('force_level')->nullable()->after('contraindications')->comment('e.g., Grade I-V, High Velocity');
            $table->string('therapist_position')->nullable()->after('force_level');
            $table->string('patient_position')->nullable()->after('therapist_position');
            $table->integer('safety_risk_score')->default(0)->after('risk_level')->comment('0-10 scale');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('skills', function (Blueprint $table) {
            $table->dropColumn(['force_level', 'therapist_position', 'patient_position', 'safety_risk_score']);
        });
    }
};
