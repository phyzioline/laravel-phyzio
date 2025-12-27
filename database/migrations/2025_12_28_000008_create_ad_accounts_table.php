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
        Schema::create('ad_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('platform'); // facebook, google
            $table->string('account_id')->nullable();
            $table->string('account_name')->nullable();
            $table->string('status')->default('inactive');
            $table->boolean('auto_tracking')->default(false);
            $table->string('connected_by')->nullable(); // User name or ID
            $table->timestamp('connected_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ad_accounts');
    }
};
