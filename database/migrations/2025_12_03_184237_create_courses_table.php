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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instructor_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('subtitle')->nullable(); // Short description
            $table->text('description')->nullable(); // HTML Detailed
            $table->decimal('price', 10, 2)->default(0);
            $table->decimal('discount_price', 10, 2)->nullable();
            $table->string('thumbnail')->nullable(); // Cover Image
            $table->string('trailer_url')->nullable(); // Preview Video
            $table->string('status')->default('draft'); // draft, review, published
            $table->string('level')->default('All Levels'); // Beginner, Intermediate, Advanced
            $table->string('language')->default('English'); // Arabic, English
            $table->json('requirements')->nullable(); // ["Basics of Biology", "Laptop"]
            $table->json('outcomes')->nullable(); // ["What you will learn 1", "outcome 2"]
            $table->text('refund_policy')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
