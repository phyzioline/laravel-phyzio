<?php

namespace App\Events\Feed;

use App\Models\FeedItem;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewPostCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $feedItem;

    /**
     * Create a new event instance.
     */
    public function __construct(FeedItem $feedItem)
    {
        $this->feedItem = $feedItem;
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
        return 'post.created';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        $author = $this->feedItem->sourceable;
        
        return [
            'id' => $this->feedItem->id,
            'type' => $this->feedItem->type,
            'title' => $this->feedItem->title,
            'description' => $this->feedItem->description,
            'media_url' => $this->feedItem->media_url,
            'author' => [
                'name' => $author ? $author->name : 'Phyzioline',
                'type' => $author ? ($author->type ?? 'user') : 'system'
            ],
            'created_at' => $this->feedItem->created_at->diffForHumans()
        ];
    }
}
