<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseModule extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'learning_objectives', // json
        'order'
    ];

    protected $casts = [
        'learning_objectives' => 'array',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function units()
    {
        return $this->hasMany(CourseUnit::class, 'module_id')->orderBy('order');
    }
}
