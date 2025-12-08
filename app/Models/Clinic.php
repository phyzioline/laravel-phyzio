<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clinic extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'slug',
        'description',
        'address',
        'city',
        'country',
        'phone',
        'email',
        'subscription_tier',
        'subscription_start',
        'subscription_end',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_deleted' => 'boolean',
        'subscription_start' => 'datetime',
        'subscription_end' => 'datetime'
    ];

    public function company()
    {
        return $this->belongsTo(User::class, 'company_id');
    }

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }

    public function appointments()
    {
        return $this->hasMany(ClinicAppointment::class);
    }
}
