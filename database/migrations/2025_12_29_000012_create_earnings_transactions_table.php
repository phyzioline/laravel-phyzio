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
        Schema::create('earnings_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('source', ['home_visit', 'course', 'clinic'])->index();
            $table->string('source_type'); // e.g., 'App\Models\HomeVisit', 'App\Models\Enrollment', 'App\Models\ClinicProgram'
            $table->unsignedBigInteger('source_id'); // ID of the source record
            $table->decimal('amount', 10, 2);
            $table->decimal('platform_fee', 10, 2)->default(0); // Platform commission
            $table->decimal('net_earnings', 10, 2); // Amount after platform fee
            $table->enum('status', ['pending', 'available', 'on_hold', 'paid'])->default('pending')->index();
            $table->date('hold_until')->nullable(); // When funds become available
            $table->date('settled_at')->nullable(); // When funds were moved to available
            $table->foreignId('payout_id')->nullable()->constrained('payouts')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();

            // Indexes for better query performance
            $table->index(['user_id', 'source']);
            $table->index(['user_id', 'status']);
            $table->index(['source_type', 'source_id']);
            $table->index('hold_until');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('earnings_transactions');
    }
};

