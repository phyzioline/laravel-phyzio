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
        Schema::table('vendor_payments', function (Blueprint $table) {
            // Add settlement tracking fields
            $table->timestamp('hold_until')->nullable()->after('vendor_earnings');
            $table->boolean('settled')->default(false)->after('hold_until');
            
            // Add indexes for settlement queries
            $table->index('hold_until');
            $table->index('settled');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendor_payments', function (Blueprint $table) {
            $table->dropColumn(['hold_until', 'settled']);
        });
    }
};
