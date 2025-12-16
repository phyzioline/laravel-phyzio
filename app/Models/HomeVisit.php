<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'therapist_id',
        'package_id',
        'location_lat',
        'location_lng',
        'address',
        'city',
        'scheduled_at',
        'arrived_at',
        'completed_at',
        'status',
        'complain_type',
        'urgency',
        'total_amount',
        'payment_method',
        'payment_status'
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'arrived_at' => 'datetime',
        'completed_at' => 'datetime',
        'location_lat' => 'float',
        'location_lng' => 'float',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'patient_id');
    }

    public function therapist()
    {
        return $this->belongsTo(User::class, 'therapist_id');
    }

    public function clinicalNotes()
    {
        return $this->hasOne(VisitClinicalNote::class);
    }

    public function package()
    {
        return $this->belongsTo(VisitPackage::class);
    }
}
