<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorSpecialtyAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'doctor_id',
        'specialty',
        'is_head',
        'priority',
        'is_active'
    ];

    protected $casts = [
        'is_head' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }

    /**
     * Get specialty display name
     */
    public function getSpecialtyNameAttribute(): string
    {
        return \App\Models\ClinicSpecialty::SPECIALTIES[$this->specialty] ?? ucfirst(str_replace('_', ' ', $this->specialty));
    }
}

