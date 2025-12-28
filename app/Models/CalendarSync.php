<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalendarSync extends Model
{
    use HasFactory;

    protected $table = 'calendar_syncs';

    protected $fillable = [
        'clinic_id',
        'user_id',
        'provider',
        'calendar_id',
        'access_token',
        'refresh_token',
        'token_expires_at',
        'sync_enabled',
        'sync_direction',
        'last_synced_at',
        'sync_settings'
    ];

    protected $casts = [
        'token_expires_at' => 'datetime',
        'last_synced_at' => 'datetime',
        'sync_enabled' => 'boolean',
        'sync_settings' => 'array'
    ];

    /**
     * Relationship: Clinic
     */
    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Relationship: User (Therapist/Doctor)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if token is expired
     */
    public function isTokenExpired(): bool
    {
        if (!$this->token_expires_at) {
            return false;
        }
        return $this->token_expires_at->isPast();
    }

    /**
     * Update last synced timestamp
     */
    public function markAsSynced(): void
    {
        $this->update(['last_synced_at' => now()]);
    }
}

