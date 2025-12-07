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
        // First, fix any existing data that might be invalid
        DB::statement("UPDATE users SET type = 'buyer' WHERE type NOT IN ('vendor', 'buyer', 'therapist') OR type IS NULL");
        
        // Change enum to string to support more roles like 'therapist' without strict enum constraints
        // Or strictly expand the enum if preferred, but string is safer for future roles.
        Schema::table('users', function (Blueprint $table) {
           $table->string('type')->nullable(false)->default('buyer')->change(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverting might be risky if we have data that violates the old enum, but we can try
        // Schema::table('users', function (Blueprint $table) {
        //    $table->enum('type', ['vendor', 'buyer'])->change();
        // });
    }
};
