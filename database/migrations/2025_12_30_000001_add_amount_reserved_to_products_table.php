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
        Schema::table('products', function (Blueprint $table) {
            // Add reserved amount column for stock reservation system
            $table->integer('amount_reserved')->default(0)->after('amount');
            
            // Add index for faster queries on available stock
            $table->index(['amount', 'amount_reserved'], 'idx_stock_availability');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex('idx_stock_availability');
            $table->dropColumn('amount_reserved');
        });
    }
};
