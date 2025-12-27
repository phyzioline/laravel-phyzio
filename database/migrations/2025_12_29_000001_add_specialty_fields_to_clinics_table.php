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
        Schema::table('clinics', function (Blueprint $table) {
            // Primary specialty (single selection for main focus)
            $table->string('primary_specialty')->nullable()->after('subscription_tier');
            
            // Flag to track if specialty has been selected
            $table->boolean('specialty_selected')->default(false)->after('primary_specialty');
            
            // Timestamp when specialty was selected
            $table->timestamp('specialty_selected_at')->nullable()->after('specialty_selected');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clinics', function (Blueprint $table) {
            $table->dropColumn([
                'primary_specialty',
                'specialty_selected',
                'specialty_selected_at'
            ]);
        });
    }
};

