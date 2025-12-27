<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform', // facebook, google, tiktok
        'account_id',
        'account_name',
        'status', // active, inactive, connected
        'auto_tracking', // boolean
        'connected_by',
        'connected_at',
    ];

    protected $casts = [
        'auto_tracking' => 'boolean',
        'connected_at' => 'datetime',
    ];
}
