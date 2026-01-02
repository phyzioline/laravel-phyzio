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
        Schema::create('feed_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feed_item_id')->constrained('feed_items')->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('parent_id')->nullable()->constrained('feed_comments')->onDelete('cascade');
            $table->text('comment_text');
            $table->string('media_url')->nullable(); // For images/gifs in comments
            $table->integer('likes_count')->default(0);
            $table->timestamps();
            
            $table->index(['feed_item_id', 'created_at']);
            $table->index('parent_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feed_comments');
    }
};
