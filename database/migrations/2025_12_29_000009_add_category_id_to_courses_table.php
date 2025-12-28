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
        // Check if columns don't already exist before adding
        if (!Schema::hasColumn('courses', 'category_id')) {
            Schema::table('courses', function (Blueprint $table) {
                $table->foreignId('category_id')->nullable()->after('instructor_id')->constrained('categories')->onDelete('set null');
            });
        }
        
        if (!Schema::hasColumn('courses', 'specialty')) {
            Schema::table('courses', function (Blueprint $table) {
                $table->string('specialty')->nullable()->after('category_id');
            });
        }
        
        if (!Schema::hasColumn('courses', 'clinical_focus')) {
            Schema::table('courses', function (Blueprint $table) {
                $table->string('clinical_focus')->nullable()->after('specialty');
            });
        }
        
        if (!Schema::hasColumn('courses', 'equipment_required')) {
            Schema::table('courses', function (Blueprint $table) {
                $table->json('equipment_required')->nullable()->after('clinical_focus');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn(['category_id', 'specialty', 'clinical_focus', 'equipment_required']);
        });
    }
};

