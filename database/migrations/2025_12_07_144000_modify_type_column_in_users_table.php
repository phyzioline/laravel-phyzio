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
        // Change enum to string to support more roles like 'therapist' without strict enum constraints
        // Or strictly expand the enum if preferred, but string is safer for future roles.
        Schema::table('users', function (Blueprint $table) {
           $table->string('type')->change(); 
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
