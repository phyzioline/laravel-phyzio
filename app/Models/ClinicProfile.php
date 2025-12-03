<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClinicProfile extends Model
{
    protected $fillable = [
        'user_id',
        'clinic_name',
        'address',
        'phone',
        'subscription_plan',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
