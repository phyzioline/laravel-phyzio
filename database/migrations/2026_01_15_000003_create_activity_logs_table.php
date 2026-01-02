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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clinic_id')->nullable()->constrained('clinics')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            // Action details
            $table->string('action'); // created, updated, deleted, viewed, etc.
            $table->string('model_type'); // Patient, Invoice, Payment, etc.
            $table->unsignedBigInteger('model_id')->nullable();
            
            // Changes
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            
            // Context
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->text('description')->nullable();
            
            // Metadata
            $table->string('route')->nullable();
            $table->string('method')->nullable(); // GET, POST, PUT, DELETE
            
            $table->timestamps();
            
            // Indexes
            $table->index('clinic_id');
            $table->index('user_id');
            $table->index(['model_type', 'model_id']);
            $table->index('action');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};

