<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    protected $fillable = [
        'user_id',
        'company_name',
        'description',
        'logo',
        'address',
        'phone',
        'website',
        'industry',
        'company_size',
        'subscription_plan',
        'status',
        'is_verified',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class, 'clinic_id', 'user_id')
            ->where('posted_by_type', 'company');
    }
}

