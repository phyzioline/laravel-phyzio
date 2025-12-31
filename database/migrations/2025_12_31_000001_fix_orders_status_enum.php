<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the status enum to include all order statuses
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'pending_payment', 'processing', 'shipped', 'delivered', 'completed', 'cancelled') DEFAULT 'pending'");
        
        // Fix the payment_status enum typo (faild -> failed)
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_status ENUM('pending', 'failed', 'paid') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original status values
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending', 'cancelled', 'completed') DEFAULT 'pending'");
        
        // Revert payment_status
        DB::statement("ALTER TABLE orders MODIFY COLUMN payment_status ENUM('pending', 'faild', 'paid') DEFAULT 'pending'");
    }
};
