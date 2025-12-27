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
        Schema::table('payouts', function (Blueprint $table) {
            $table->foreignId('therapist_id')->nullable()->after('vendor_id')->constrained('users', 'id')->cascadeOnDelete();
            $table->index('therapist_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payouts', function (Blueprint $table) {
            $table->dropForeign(['therapist_id']);
            $table->dropIndex(['therapist_id']);
            $table->dropColumn('therapist_id');
        });
    }
};

