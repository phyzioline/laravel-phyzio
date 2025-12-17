<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'thumbnail',
        'price',
        'seats', // Added seats
        'instructor_id',
        'category_id',
        'status', // draft, published, archived
        // Enhanced Fields
        'specialty',
        'level',
        'total_hours',
        'practical_hours',
        'clinical_focus',
        'equipment_required', // json
        'accreditation_status',
        'subscription_included', // boolean
        'video_file', // from previous migration
        'type' // from previous migration
    ];

    protected $casts = [
        'equipment_required' => 'array',
        'subscription_included' => 'boolean',
        'total_hours' => 'decimal:2',
        'practical_hours' => 'decimal:2',
    ];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function modules()
    {
        return $this->hasMany(CourseModule::class)->orderBy('order');
    }

    public function units()
    {
        return $this->hasManyThrough(CourseUnit::class, CourseModule::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'course_skills')
                    ->withPivot('mastery_level_required');
    }

    public function clinicalCases()
    {
        return $this->hasMany(ClinicalCase::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }
}
