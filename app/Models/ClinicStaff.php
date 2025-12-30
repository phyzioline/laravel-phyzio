<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClinicStaff extends Model
{
    protected $table = 'clinic_staff';

    protected $fillable = [
        'clinic_id',
        'user_id',
        'role',
        'is_active',
        'hired_date',
        'terminated_date',
        'notes',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'hired_date' => 'date',
        'terminated_date' => 'date',
    ];

    /**
     * Get the clinic this staff belongs to.
     */
    public function clinic(): BelongsTo
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Get the user (therapist/doctor/admin).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get only active staff.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->whereNull('terminated_date');
    }

    /**
     * Scope to get staff by role.
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }
}
