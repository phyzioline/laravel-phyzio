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
        Schema::create('product_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products', 'id')->cascadeOnDelete();
            $table->integer('views')->default(0);
            $table->integer('clicks')->default(0);
            $table->integer('add_to_cart_count')->default(0);
            $table->integer('purchases')->default(0);
            $table->decimal('conversion_rate', 5, 2)->default(0); // Percentage
            $table->decimal('velocity', 10, 2)->default(0); // Sales per day
            $table->integer('total_sales')->default(0);
            $table->decimal('revenue', 10, 2)->default(0);
            $table->date('last_sale_date')->nullable();
            $table->timestamps();

            $table->index('product_id');
            $table->index('conversion_rate');
            $table->index('velocity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_metrics');
    }
};

