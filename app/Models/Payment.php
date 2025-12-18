<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'paymentable_type',
        'paymentable_id',
        'type',
        'amount',
        'currency',
        'status',
        'method',
        'reference',
        'meta',
        'original_amount',
        'original_currency',
        'exchange_rate',
        'exchanged_at'
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'meta' => 'array',
        'original_amount' => 'decimal:2',
        'exchange_rate' => 'decimal:6',
        'exchanged_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function paymentable()
    {
        return $this->morphTo();
    }

    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
}
