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
        Schema::create('returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_item_id')->constrained('items_orders', 'id')->cascadeOnDelete();
            $table->foreignId('shipment_id')->nullable()->constrained('shipments', 'id')->nullOnDelete();
            $table->text('reason');
            $table->enum('status', ['requested', 'approved', 'rejected', 'refunded', 'cancelled'])->default('requested');
            $table->decimal('refund_amount', 10, 2)->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users', 'id')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();

            // Indexes for better query performance
            $table->index('order_item_id');
            $table->index('shipment_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('returns');
    }
};
