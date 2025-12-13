<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TherapistSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'therapist_id',
        'day_of_week',
        'start_time',
        'end_time',
        'slot_duration',
        'break_duration',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function therapist()
    {
        return $this->belongsTo(User::class, 'therapist_id');
    }
}
