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
        Schema::table('items_orders', function (Blueprint $table) {
            // Medical Engineer Service tracking in orders
            $table->boolean('engineer_selected')->default(false)->after('price')->comment('Whether customer selected engineer service');
            $table->decimal('engineer_price', 10, 2)->nullable()->after('engineer_selected')->comment('Price paid for engineer service');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items_orders', function (Blueprint $table) {
            $table->dropColumn(['engineer_selected', 'engineer_price']);
        });
    }
};

