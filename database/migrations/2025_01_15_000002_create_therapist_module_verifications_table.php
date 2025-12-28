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
        Schema::create('therapist_module_verifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('therapist_profile_id')->constrained('therapist_profiles')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->enum('module_type', ['home_visit', 'courses', 'clinic'])->index();
            $table->enum('status', ['pending', 'under_review', 'approved', 'rejected'])->default('pending')->index();
            $table->text('admin_note')->nullable();
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamp('verified_at')->nullable();
            $table->timestamps();

            // Ensure one verification record per module per therapist
            $table->unique(['therapist_profile_id', 'module_type'], 'tmv_profile_module_unique');
            $table->index(['user_id', 'module_type'], 'tmv_user_module_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('therapist_module_verifications');
    }
};

