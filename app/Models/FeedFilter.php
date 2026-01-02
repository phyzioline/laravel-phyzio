<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeedFilter extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'filter_name',
        'filter_criteria',
        'is_default'
    ];

    protected $casts = [
        'filter_criteria' => 'array',
        'is_default' => 'boolean',
    ];

    /**
     * Get the user that owns the filter
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
