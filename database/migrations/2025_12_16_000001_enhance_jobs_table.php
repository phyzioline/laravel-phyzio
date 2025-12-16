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
        Schema::table('jobs', function (Blueprint $table) {
            $table->json('specialty')->nullable();
            $table->json('techniques')->nullable();
            $table->json('equipment')->nullable();
            $table->string('experience_level')->nullable();
            $table->string('urgency_level')->default('normal'); // normal, urgent
            $table->string('salary_type')->default('fixed'); // fixed, per_session, commission
            $table->json('benefits')->nullable();
            $table->integer('openings_count')->default(1);
            $table->boolean('featured')->default(false);
            $table->string('posted_by_type')->nullable(); // clinic, company
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jobs', function (Blueprint $table) {
            $table->dropColumn([
                'specialty',
                'techniques',
                'equipment',
                'experience_level',
                'urgency_level',
                'salary_type',
                'benefits',
                'openings_count',
                'featured',
                'posted_by_type',
            ]);
        });
    }
};
