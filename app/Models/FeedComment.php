<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class FeedComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'feed_item_id',
        'user_id',
        'parent_id',
        'comment_text',
        'media_url',
        'likes_count'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the feed item this comment belongs to
     */
    public function feedItem()
    {
        return $this->belongsTo(FeedItem::class, 'feed_item_id');
    }

    /**
     * Get the user who posted this comment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent comment (if this is a reply)
     */
    public function parent()
    {
        return $this->belongsTo(FeedComment::class, 'parent_id');
    }

    /**
     * Get all replies to this comment
     */
    public function replies()
    {
        return $this->hasMany(FeedComment::class, 'parent_id')->with('user', 'replies');
    }

    /**
     * Get all likes for this comment
     */
    public function likes()
    {
        return $this->hasMany(CommentLike::class, 'comment_id');
    }

    /**
     * Check if current user liked this comment
     */
    public function getLikedByUserAttribute()
    {
        if (!Auth::check()) return false;

        return $this->likes()
                    ->where('user_id', Auth::id())
                    ->exists();
    }

    /**
     * Scope to get only top-level comments (not replies)
     */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Scope to get comments with replies loaded
     */
    public function scopeWithReplies($query)
    {
        return $query->with(['replies' => function($q) {
            $q->with('user')->latest();
        }]);
    }
}
