<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    protected $fillable = [
        'user_id',
        'email',
        'name',
        'phone',
        'address',
        'city',
        'governorate',
        'lat',
        'lng',
        'is_default',
        'address_type',
    ];

    protected $casts = [
        'is_default' => 'boolean',
        'lat' => 'decimal:7',
        'lng' => 'decimal:7',
    ];

    /**
     * Get the user that owns the address.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for default address.
     */
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Scope for user addresses.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for guest addresses by email.
     */
    public function scopeForGuest($query, $email)
    {
        return $query->whereNull('user_id')->where('email', $email);
    }
}

