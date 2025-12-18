<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendor_payments', function (Blueprint $table) {
            if (!Schema::hasColumn('vendor_payments', 'appointment_id')) {
                $table->unsignedBigInteger('appointment_id')->nullable()->after('order_id');
                $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('set null');
            }
        });
    }

    public function down(): void
    {
        Schema::table('vendor_payments', function (Blueprint $table) {
            if (Schema::hasColumn('vendor_payments', 'appointment_id')) {
                $table->dropForeign(['appointment_id']);
                $table->dropColumn('appointment_id');
            }
        });
    }
};
