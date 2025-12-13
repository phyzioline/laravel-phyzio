<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'title',
        'description',
        'type',
        'location',
        'salary_range',
        'file_path',
        'is_active'
    ];

    public function clinic()
    {
        return $this->belongsTo(User::class, 'clinic_id');
    }
}
