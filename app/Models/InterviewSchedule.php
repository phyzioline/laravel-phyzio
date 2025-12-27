<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InterviewSchedule extends Model
{
    protected $fillable = [
        'job_application_id',
        'job_id',
        'therapist_id',
        'company_id',
        'scheduled_at',
        'interview_type',
        'location',
        'meeting_link',
        'notes',
        'status',
        'feedback',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
    ];

    public function jobApplication()
    {
        return $this->belongsTo(JobApplication::class);
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function therapist()
    {
        return $this->belongsTo(User::class, 'therapist_id');
    }

    public function company()
    {
        return $this->belongsTo(User::class, 'company_id');
    }
}

