<?php

namespace App\Services\Feed;

use App\Models\FeedItem;
use App\Models\FeedInteraction;
use Illuminate\Support\Facades\Auth;

class FeedTrackingService
{
    /**
     * Log a user interaction.
     * This data would be exported to Python/AI models later for training.
     */
    public function logInteraction($feedItemId, $type, $meta = [])
    {
        if (!Auth::check()) return;

        $interaction = FeedInteraction::create([
            'user_id' => Auth::id(),
            'feed_item_id' => $feedItemId,
            'type' => $type,
            'meta_data' => $meta
        ]);

        // Real-time counter update (Cheap aggregation)
        if (in_array($type, ['view', 'click', 'like', 'share'])) {
            FeedItem::where('id', $feedItemId)->increment("{$type}s_count");
        }

        return $interaction;
    }

    /**
     * "Like" toggle logic.
     */
    public function toggleLike($feedItemId)
    {
        $existing = FeedInteraction::where('user_id', Auth::id())
                        ->where('feed_item_id', $feedItemId)
                        ->where('type', 'like')
                        ->first();

        if ($existing) {
            $existing->delete();
            FeedItem::where('id', $feedItemId)->decrement('likes_count');
            return false; // unliked
        } else {
            $this->logInteraction($feedItemId, 'like');
            return true; // liked
        }
    }
}
