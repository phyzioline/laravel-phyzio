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
        Schema::create('equipment_inventory', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
            $table->string('name');
            $table->string('type'); // shockwave, biofeedback, ultrasound, tens, laser, treadmill, balance_board, etc.
            $table->text('description')->nullable();
            $table->integer('quantity')->default(1);
            $table->integer('available_quantity')->default(1);
            $table->boolean('is_active')->default(true);
            $table->json('specifications')->nullable(); // Additional specs
            $table->timestamps();
            
            $table->index(['clinic_id', 'type']);
            $table->index('is_active');
        });

        Schema::create('equipment_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained('equipment_inventory')->onDelete('cascade');
            $table->foreignId('appointment_id')->nullable()->constrained('clinic_appointments')->onDelete('cascade');
            $table->foreignId('clinic_id')->constrained('clinics')->onDelete('cascade');
            $table->dateTime('reserved_from');
            $table->dateTime('reserved_to');
            $table->enum('status', ['reserved', 'in_use', 'returned', 'cancelled'])->default('reserved');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['equipment_id', 'status']);
            $table->index(['appointment_id']);
            $table->index(['reserved_from', 'reserved_to']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_reservations');
        Schema::dropIfExists('equipment_inventory');
    }
};

