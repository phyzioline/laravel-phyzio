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
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users', 'id')->cascadeOnDelete();
            $table->string('email')->nullable(); // For guest addresses
            $table->string('name');
            $table->string('phone');
            $table->text('address');
            $table->string('city')->nullable();
            $table->string('governorate')->nullable();
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->boolean('is_default')->default(false);
            $table->string('address_type')->default('home'); // home, work, clinic, etc.
            $table->timestamps();

            $table->index('user_id');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};

