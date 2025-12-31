<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialAuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'clinic_id',
        'action',
        'entity_type',
        'entity_id',
        'user_id',
        'old_values',
        'new_values',
        'notes',
        'created_at' // Required since $timestamps = false
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime'
    ];

    public $timestamps = false; // Only use created_at

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Log a financial action
    public static function log($clinicId, $action, $entityType, $entityId, $userId, $oldValues = null, $newValues = null, $notes = null)
    {
        return static::create([
            'clinic_id' => $clinicId,
            'action' => $action,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'user_id' => $userId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'notes' => $notes,
            'created_at' => now()
        ]);
    }
}

