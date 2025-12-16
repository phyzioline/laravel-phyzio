<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobRequirement extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_id',
        'license_required',
        'min_years_experience',
        'gender_preference',
        'languages',
        'certifications',
    ];

    protected $casts = [
        'license_required' => 'boolean',
        'languages' => 'array',
        'certifications' => 'array',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }
}
