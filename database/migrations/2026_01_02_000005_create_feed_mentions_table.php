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
        Schema::create('feed_mentions', function (Blueprint $table) {
            $table->id();
            $table->string('mentionable_type'); // FeedItem or FeedComment
            $table->unsignedBigInteger('mentionable_id');
            $table->foreignId('mentioned_user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('mentioner_user_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            $table->index(['mentionable_type', 'mentionable_id']);
            $table->index('mentioned_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feed_mentions');
    }
};
