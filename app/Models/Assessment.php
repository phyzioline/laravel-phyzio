<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'unit_id',
        'course_id',
        'assessment_type', // mcq, case_study, video_submission
        'pass_score'
    ];

    public function unit()
    {
        return $this->belongsTo(CourseUnit::class);
    }
    
    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
