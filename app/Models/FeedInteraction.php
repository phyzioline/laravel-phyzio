<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedInteraction extends Model
{
    use HasFactory;
    
    public $timestamps = false; // We only use created_at manually

    protected $fillable = [
        'user_id',
        'feed_item_id',
        'type',
        'meta_data'
    ];

    protected $casts = [
        'meta_data' => 'array'
    ];

    public function feedItem()
    {
        return $this->belongsTo(FeedItem::class);
    }
}
