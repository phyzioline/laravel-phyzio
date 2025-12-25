<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $table = 'clinic_jobs';

    protected $fillable = [
        'clinic_id',
        'title',
        'description',
        'type',
        'location',
        'salary_range',
        'file_path',
        'is_active',
        'specialty',
        'techniques',
        'equipment',
        'experience_level',
        'urgency_level',
        'salary_type',
        'benefits',
        'openings_count',
        'featured',
        'posted_by_type',
    ];

    protected $casts = [
        'specialty' => 'array',
        'techniques' => 'array',
        'equipment' => 'array',
        'benefits' => 'array',
        'is_active' => 'boolean',
        'featured' => 'boolean',
    ];

    public function clinic()
    {
        return $this->belongsTo(User::class, 'clinic_id');
    }

    public function requirements()
    {
        return $this->hasOne(JobRequirement::class);
    }

    public function applications()
    {
        return $this->hasMany(JobApplication::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter jobs by company
     */
    public function scopeForCompany($query, $companyId)
    {
        return $query->where('clinic_id', $companyId)
                    ->where('posted_by_type', 'company');
    }

    /**
     * Scope to filter jobs by clinic
     */
    public function scopeForClinic($query, $clinicId)
    {
        return $query->where('clinic_id', $clinicId)
                    ->where('posted_by_type', 'clinic');
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'job_skills')
                    ->withPivot(['required_level', 'is_mandatory'])
                    ->withTimestamps();
    }
}
