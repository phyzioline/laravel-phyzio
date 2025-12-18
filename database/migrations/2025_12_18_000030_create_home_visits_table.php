<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('home_visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('therapist_id')->nullable()->constrained('users')->onDelete('set null');
            // Assuming packages table exists? If not nullable.
            $table->unsignedBigInteger('package_id')->nullable(); 
            
            $table->decimal('location_lat', 10, 8)->nullable();
            $table->decimal('location_lng', 11, 8)->nullable();
            $table->text('address')->nullable();
            $table->string('city')->nullable();
            
            $table->timestamp('scheduled_at')->nullable();
            $table->timestamp('arrived_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            
            $table->string('status')->default('requested'); // requested, accepted, on_way, in_session, completed, cancelled
            $table->string('complain_type')->nullable();
            $table->string('urgency')->default('normal');
            
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('payment_method')->nullable();
            $table->string('payment_status')->default('pending');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('home_visits');
    }
};
