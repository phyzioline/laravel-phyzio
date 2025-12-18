<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendor_payments', function (Blueprint $table) {
            // Make order_id and order_item_id nullable to support non-order payouts (courses, appointments)
            if (Schema::hasColumn('vendor_payments', 'order_id')) {
                $table->unsignedBigInteger('order_id')->nullable()->change();
            }
            if (Schema::hasColumn('vendor_payments', 'order_item_id')) {
                $table->unsignedBigInteger('order_item_id')->nullable()->change();
            }
        });
    }

    public function down(): void
    {
        Schema::table('vendor_payments', function (Blueprint $table) {
            if (Schema::hasColumn('vendor_payments', 'order_id')) {
                $table->unsignedBigInteger('order_id')->nullable(false)->change();
            }
            if (Schema::hasColumn('vendor_payments', 'order_item_id')) {
                $table->unsignedBigInteger('order_item_id')->nullable(false)->change();
            }
        });
    }
};
