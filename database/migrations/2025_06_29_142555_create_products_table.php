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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users','id')->cascadeOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories','id')->cascadeOnDelete();
            $table->foreignId('sub_category_id')->nullable()->constrained('sub_categories','id')->cascadeOnDelete();
            $table->string('product_name_ar');
            $table->string('product_name_en');
            $table->string('product_price');
            $table->text('short_description_ar')->nullable();
            $table->text('short_description_en')->nullable();
            $table->text('long_description_ar')->nullable();
            $table->text('long_description_en')->nullable();
            $table->unsignedBigInteger('amount')->nullable();
            $table->string('sku')->unique();
            $table->enum('status',['active','inactive'])->default('inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
