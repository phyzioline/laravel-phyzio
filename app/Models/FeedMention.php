<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedMention extends Model
{
    use HasFactory;

    protected $fillable = [
        'mentionable_type',
        'mentionable_id',
        'mentioned_user_id',
        'mentioner_user_id'
    ];

    /**
     * Get the mentioned user
     */
    public function mentionedUser()
    {
        return $this->belongsTo(User::class, 'mentioned_user_id');
    }

    /**
     * Get the user who created the mention
     */
    public function mentioner()
    {
        return $this->belongsTo(User::class, 'mentioner_user_id');
    }

    /**
     * Get the mentionable model (FeedItem or FeedComment)
     */
    public function mentionable()
    {
        return $this->morphTo();
    }
}
