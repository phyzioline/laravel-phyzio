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
        Schema::create('vendor_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained('users', 'id')->cascadeOnDelete();
            $table->foreignId('order_id')->constrained('orders', 'id')->cascadeOnDelete();
            $table->foreignId('order_item_id')->constrained('items_orders', 'id')->cascadeOnDelete();
            $table->decimal('product_amount', 10, 2);
            $table->unsignedInteger('quantity');
            $table->decimal('subtotal', 10, 2);
            $table->decimal('commission_rate', 5, 2)->default(15.00); // Percentage
            $table->decimal('commission_amount', 10, 2);
            $table->decimal('shipping_fee', 10, 2)->default(0);
            $table->decimal('vendor_earnings', 10, 2);
            $table->enum('status', ['pending', 'paid', 'cancelled'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->string('payment_reference')->nullable();
            $table->timestamps();

            // Indexes for better query performance
            $table->index('vendor_id');
            $table->index('status');
            $table->index('order_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_payments');
    }
};
