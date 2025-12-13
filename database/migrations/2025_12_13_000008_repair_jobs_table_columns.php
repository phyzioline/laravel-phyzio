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
        // Ensure table exists (create if not)
        if (!Schema::hasTable('jobs')) {
            Schema::create('jobs', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
            });
        }

        Schema::table('jobs', function (Blueprint $table) {
            if (!Schema::hasColumn('jobs', 'clinic_id')) {
                $table->foreignId('clinic_id')->nullable()->constrained('users')->onDelete('cascade');
            }
            if (!Schema::hasColumn('jobs', 'title')) {
                $table->string('title');
            }
            if (!Schema::hasColumn('jobs', 'description')) {
                $table->text('description');
            }
            if (!Schema::hasColumn('jobs', 'type')) {
                $table->string('type'); // job or training
            }
            if (!Schema::hasColumn('jobs', 'location')) {
                $table->string('location')->nullable();
            }
            if (!Schema::hasColumn('jobs', 'salary_range')) {
                $table->string('salary_range')->nullable();
            }
            if (!Schema::hasColumn('jobs', 'file_path')) {
                $table->string('file_path')->nullable();
            }
            if (!Schema::hasColumn('jobs', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We generally don't want to drop columns in a repair migration effectively
        // but for correctness:
        if (Schema::hasTable('jobs')) {
            Schema::table('jobs', function (Blueprint $table) {
                // Remove columns if they exist? Maybe risky. Leaving empty for safety.
            });
        }
    }
};
