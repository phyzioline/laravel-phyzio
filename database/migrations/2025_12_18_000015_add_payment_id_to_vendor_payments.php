<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendor_payments', function (Blueprint $table) {
            if (! Schema::hasColumn('vendor_payments', 'payment_id')) {
                $table->foreignId('payment_id')->nullable()->constrained('payments')->nullOnDelete()->after('order_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('vendor_payments', function (Blueprint $table) {
            if (Schema::hasColumn('vendor_payments', 'payment_id')) {
                $table->dropConstrainedForeignId('payment_id');
            }
        });
    }
};
