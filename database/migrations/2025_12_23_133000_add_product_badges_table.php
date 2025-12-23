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
        Schema::create('product_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products', 'id')->cascadeOnDelete();
            $table->enum('badge_type', ['best_seller', 'top_clinic_choice', 'physio_recommended', 'fast_moving', 'new_arrival', 'trending'])->nullable();
            $table->integer('priority')->default(0); // Higher priority badges shown first
            $table->date('expires_at')->nullable();
            $table->timestamps();

            $table->index('product_id');
            $table->index('badge_type');
            $table->index('priority');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_badges');
    }
};

