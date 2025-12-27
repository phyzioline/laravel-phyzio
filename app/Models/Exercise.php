<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'video_url',
        'image_url',
        'difficulty_level', // e.g., Beginner, Intermediate, Advanced
        'target_muscle_group',
        'equipment_needed',
        'instructions',
    ];

    protected $casts = [
        'equipment_needed' => 'array',
        'instructions' => 'array',
    ];
}
