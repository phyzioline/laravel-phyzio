<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TherapistGeoStatus extends Model
{
    use HasFactory;
    
    protected $table = 'therapist_geo_status';

    protected $fillable = [
        'user_id',
        'is_online',
        'current_lat',
        'current_lng',
        'last_updated_at',
        'current_visit_id'
    ];

    protected $casts = [
        'is_online' => 'boolean',
        'last_updated_at' => 'datetime',
        'current_lat' => 'float',
        'current_lng' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
