<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobTemplate extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'title',
        'description',
        'type',
        'location',
        'salary_type',
        'salary_range',
        'specialty',
        'techniques',
        'equipment',
        'experience_level',
        'urgency_level',
        'openings_count',
        'min_years_experience',
        'gender_preference',
        'license_required',
    ];

    protected $casts = [
        'specialty' => 'array',
        'techniques' => 'array',
        'equipment' => 'array',
        'license_required' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

