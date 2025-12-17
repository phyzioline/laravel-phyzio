<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('courses')) {
            Schema::table('courses', function (Blueprint $table) {
                // Ensure 'level' is large enough. "Intermediate" is 12 chars.
                // Using change() requires doctrine/dbal, but we can try just modifying if supported
                // or assume standard string is fine.
                // Since this is a fix, let's explicitly make it string(191) or similar.
                $table->string('level', 191)->change();
            });
        }
    }

    public function down(): void
    {
        // No down needed really, but we can revert length if we knew it
    }
};
