<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'sourceable_id',
        'sourceable_type',
        'title',
        'description',
        'media_url',
        'action_link',
        'action_text',
        'target_audience',
        'specialty_tags',
        'ai_relevance_base_score',
        'views_count',
        'clicks_count',
        'likes_count',
        'status',
        'scheduled_at'
    ];

    protected $casts = [
        'target_audience' => 'array',
        'specialty_tags' => 'array',
        'scheduled_at' => 'datetime',
        'ai_relevance_base_score' => 'float'
    ];

    /**
     * Get the parent sourceable model (Course, Product, etc).
     */
    public function sourceable()
    {
        return $this->morphTo();
    }

    public function interactions()
    {
        return $this->hasMany(FeedInteraction::class);
    }
    
    // Scope to fetch active feed for a specific user role/specialty
    public function scopeForUser($query, \App\Models\User $user)
    {
        return $query->where('status', 'active')
                     ->where('scheduled_at', '<=', now())
                     ->where(function($q) use ($user) {
                         // 1. Audience Match (or 'all')
                         $q->whereJsonContains('target_audience', $user->type)
                           ->orWhereJsonContains('target_audience', 'all');
                     })
                     ->orderBy('ai_relevance_base_score', 'desc') // Simple personalization start
                     ->latest();
    }

    public function getLikedByUserAttribute()
    {
        if (!Auth::check()) return false;
        
        // This is not efficient for N+1 but fine for MVP. 
        // In prod, load this via 'withCount' or relationship checking
        return $this->interactions()
                    ->where('user_id', Auth::id())
                    ->where('type', 'like')
                    ->exists();
    }
}
