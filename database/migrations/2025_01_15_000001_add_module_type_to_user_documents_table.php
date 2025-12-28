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
        Schema::table('user_documents', function (Blueprint $table) {
            $table->string('module_type')->nullable()->after('document_type')->comment('home_visit, courses, clinic, or null for general documents');
            $table->index(['user_id', 'module_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_documents', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'module_type']);
            $table->dropColumn('module_type');
        });
    }
};

