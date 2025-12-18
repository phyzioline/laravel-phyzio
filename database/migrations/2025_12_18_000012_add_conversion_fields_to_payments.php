<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'original_amount')) {
                $table->decimal('original_amount', 12, 2)->nullable()->after('amount');
            }
            if (!Schema::hasColumn('payments', 'original_currency')) {
                $table->string('original_currency', 10)->nullable()->after('original_amount');
            }
            if (!Schema::hasColumn('payments', 'exchange_rate')) {
                $table->decimal('exchange_rate', 18, 8)->nullable()->after('original_currency');
            }
            if (!Schema::hasColumn('payments', 'exchanged_at')) {
                $table->timestamp('exchanged_at')->nullable()->after('exchange_rate');
            }
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'exchanged_at')) {
                $table->dropColumn('exchanged_at');
            }
            if (Schema::hasColumn('payments', 'exchange_rate')) {
                $table->dropColumn('exchange_rate');
            }
            if (Schema::hasColumn('payments', 'original_currency')) {
                $table->dropColumn('original_currency');
            }
            if (Schema::hasColumn('payments', 'original_amount')) {
                $table->dropColumn('original_amount');
            }
        });
    }
};
