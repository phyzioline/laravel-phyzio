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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change(); // Allow null for guest orders
            $table->string('email')->nullable()->after('user_id'); // Guest email
            $table->string('guest_token')->nullable()->after('email'); // For guest order tracking
            $table->boolean('is_guest_order')->default(false)->after('guest_token');
            $table->foreignId('address_id')->nullable()->after('is_guest_order')->constrained('user_addresses', 'id')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['address_id']);
            $table->dropColumn(['email', 'guest_token', 'is_guest_order', 'address_id']);
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};

