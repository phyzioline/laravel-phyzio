<?php

namespace App\Events\Feed;

use App\Models\FeedComment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewCommentAdded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;
    public $feedItemId;

    /**
     * Create a new event instance.
     */
    public function __construct(FeedComment $comment)
    {
        $this->comment = $comment;
        $this->feedItemId = $comment->feed_item_id;
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
        return 'comment.added';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'feed_item_id' => $this->feedItemId,
            'comment_id' => $this->comment->id,
            'comment_text' => $this->comment->comment_text,
            'user' => [
                'name' => $this->comment->user->name,
                'id' => $this->comment->user->id
            ],
            'created_at' => $this->comment->created_at->diffForHumans()
        ];
    }
}
