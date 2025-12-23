<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PayoutSetting extends Model
{
    protected $fillable = [
        'hold_period_days',
        'auto_payout_enabled',
        'minimum_payout',
        'auto_payout_frequency',
    ];

    protected $casts = [
        'hold_period_days' => 'integer',
        'auto_payout_enabled' => 'boolean',
        'minimum_payout' => 'decimal:2',
    ];

    /**
     * Get the single payout setting instance (singleton pattern).
     */
    public static function getSettings()
    {
        return static::firstOrCreate([], [
            'hold_period_days' => 7,
            'auto_payout_enabled' => true,
            'minimum_payout' => 100.00,
            'auto_payout_frequency' => 'weekly',
        ]);
    }
}

