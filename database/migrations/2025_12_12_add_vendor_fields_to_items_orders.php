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
            $table->foreignId('vendor_id')->nullable()->after('product_id')->constrained('users', 'id')->cascadeOnDelete();
            $table->decimal('commission_rate', 5, 2)->default(15.00)->after('total');
            $table->decimal('commission_amount', 10, 2)->default(0)->after('commission_rate');
            $table->decimal('shipping_fee', 10, 2)->default(0)->after('commission_amount');
            $table->decimal('vendor_earnings', 10, 2)->default(0)->after('shipping_fee');

            // Index for vendor queries
            $table->index('vendor_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items_orders', function (Blueprint $table) {
            $table->dropForeign(['vendor_id']);
            $table->dropColumn(['vendor_id', 'commission_rate', 'commission_amount', 'shipping_fee', 'vendor_earnings']);
        });
    }
};
