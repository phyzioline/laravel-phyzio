<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Feed Items (The Content Cards)
        Schema::create('feed_items', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // course, product, job, article, home_visit_alert, tip
            
            // Polymorphic relation to the source content (e.g., Course ID 5)
            $table->nullableMorphs('sourceable'); 
            
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('media_url')->nullable(); // Image/Video
            $table->string('action_link')->nullable(); // e.g., /courses/5
            $table->string('action_text')->default('View More');
            
            // Targeting & AI Fields
            $table->json('target_audience')->nullable(); // ['therapist', 'patient', 'clinic']
            $table->json('specialty_tags')->nullable(); // ['ortho', 'peds']
            $table->decimal('ai_relevance_base_score', 5, 2)->default(1.0); // Base weight
            
            // Social Proof Counters (Cached for performance)
            $table->integer('views_count')->default(0);
            $table->integer('clicks_count')->default(0);
            $table->integer('likes_count')->default(0);
            $table->integer('shares_count')->default(0);
            
            $table->enum('status', ['draft', 'scheduled', 'active', 'archived'])->default('draft');
            $table->timestamp('scheduled_at')->nullable();
            
            $table->timestamps();
            
            // Indexes for fast feed queries
            $table->index(['status', 'scheduled_at']);
            $table->index('type');
        });

        // 2. Feed Interactions (The User Tracking Data - "Fuel for AI")
        Schema::create('feed_interactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('feed_item_id')->constrained('feed_items')->onDelete('cascade');
            
            $table->enum('type', ['view', 'click', 'like', 'share', 'bookmark', 'dismiss']);
            
            // Meta data for deep tracking
            // e.g., { time_spent_ms: 1200, source: 'mobile_app', scroll_depth: 50% }
            $table->json('meta_data')->nullable(); 
            
            $table->timestamp('created_at')->useCurrent();
            
            // Composite index to check "Did user X click item Y?" quickly
            $table->index(['user_id', 'feed_item_id', 'type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('feed_interactions');
        Schema::dropIfExists('feed_items');
    }
};
