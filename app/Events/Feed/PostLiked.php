<?php

namespace App\Events\Feed;

use App\Models\FeedItem;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PostLiked implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $feedItemId;
    public $likesCount;

    /**
     * Create a new event instance.
     */
    public function __construct($feedItemId, $likesCount)
    {
        $this->feedItemId = $feedItemId;
        $this->likesCount = $likesCount;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): Channel
    {
        return new Channel('feed');
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'post.liked';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'feed_item_id' => $this->feedItemId,
            'likes_count' => $this->likesCount
        ];
    }
}
