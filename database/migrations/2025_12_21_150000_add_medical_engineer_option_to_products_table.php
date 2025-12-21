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
        Schema::table('products', function (Blueprint $table) {
            // Medical Engineer Service Option
            $table->boolean('has_engineer_option')->default(false)->after('status')->comment('Whether medical engineer service is available');
            $table->decimal('engineer_price', 10, 2)->nullable()->after('has_engineer_option')->comment('Extra charge for medical engineer service');
            $table->boolean('engineer_required')->default(false)->after('engineer_price')->comment('Whether engineer service is mandatory for this product');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['has_engineer_option', 'engineer_price', 'engineer_required']);
        });
    }
};

