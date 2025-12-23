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
        Schema::create('payout_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('hold_period_days')->default(7);
            $table->boolean('auto_payout_enabled')->default(true);
            $table->decimal('minimum_payout', 10, 2)->default(100.00);
            $table->string('auto_payout_frequency')->default('weekly'); // weekly, biweekly, monthly
            $table->timestamps();
        });

        // Insert default settings
        DB::table('payout_settings')->insert([
            'hold_period_days' => 7,
            'auto_payout_enabled' => true,
            'minimum_payout' => 100.00,
            'auto_payout_frequency' => 'weekly',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payout_settings');
    }
};

