<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'title',
        'unit_type', // theory, demo, case, assessment
        'duration_minutes',
        'safety_notes',
        'contraindications',
        'video_url',
        'content',
        'order'
    ];

    public function module()
    {
        return $this->belongsTo(CourseModule::class);
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class, 'unit_id');
    }
}
