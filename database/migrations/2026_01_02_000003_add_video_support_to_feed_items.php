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
        Schema::table('feed_items', function (Blueprint $table) {
            $table->string('video_url')->nullable()->after('media_url');
            $table->string('video_thumbnail')->nullable()->after('video_url');
            $table->integer('video_duration')->nullable()->comment('Duration in seconds')->after('video_thumbnail');
            $table->enum('video_provider', ['upload', 'youtube', 'vimeo'])->nullable()->after('video_duration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('feed_items', function (Blueprint $table) {
            $table->dropColumn(['video_url', 'video_thumbnail', 'video_duration', 'video_provider']);
        });
    }
};
