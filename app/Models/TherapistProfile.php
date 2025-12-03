<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TherapistProfile extends Model
{
    protected $fillable = [
        'user_id',
        'bio',
        'specialization',
        'license_number',
        'hourly_rate',
        'home_visit_rate',
        'rating',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
