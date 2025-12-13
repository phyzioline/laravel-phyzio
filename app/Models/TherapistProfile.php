<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TherapistProfile extends Model
{
    protected $fillable = [
        'user_id',
        'bio',
        'specialization',
        'years_experience',
        'license_number',
        'license_document',
        'id_document',
        'hourly_rate',
        'home_visit_rate',
        'available_areas',
        'working_hours',
        'rating',
        'total_reviews',
        'status',
        'verified_at',
        'total_earnings',
        'platform_balance',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'iban',
        'swift_code',
        'can_access_clinic',
        'can_access_instructor'
    ];

    protected $casts = [
        'available_areas' => 'array',
        'working_hours' => 'array',
        'verified_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class, 'therapist_id', 'user_id');
    }
}
