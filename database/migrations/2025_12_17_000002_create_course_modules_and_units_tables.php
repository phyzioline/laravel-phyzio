<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('course_modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->json('learning_objectives')->nullable();
            $table->integer('order')->default(0);
            $table->timestamps();
        });

        Schema::create('course_units', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('course_modules')->onDelete('cascade');
            $table->string('title');
            $table->enum('unit_type', ['theory', 'demo', 'case', 'assessment'])->default('theory');
            $table->integer('duration_minutes')->default(0);
            $table->text('safety_notes')->nullable();
            $table->text('contraindications')->nullable();
            $table->string('video_url')->nullable(); 
            $table->text('content')->nullable(); // For text-based theory or instructions
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('course_units');
        Schema::dropIfExists('course_modules');
    }
};
