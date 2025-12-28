<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentInventory extends Model
{
    use HasFactory;

    protected $table = 'equipment_inventory';

    protected $fillable = [
        'clinic_id',
        'name',
        'type',
        'description',
        'quantity',
        'available_quantity',
        'is_active',
        'specifications'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'available_quantity' => 'integer',
        'is_active' => 'boolean',
        'specifications' => 'array'
    ];

    /**
     * Relationship: Clinic
     */
    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }

    /**
     * Relationship: Reservations
     */
    public function reservations()
    {
        return $this->hasMany(EquipmentReservation::class, 'equipment_id');
    }

    /**
     * Get active reservations
     */
    public function activeReservations()
    {
        return $this->reservations()
            ->whereIn('status', ['reserved', 'in_use'])
            ->where('reserved_to', '>=', now());
    }

    /**
     * Check if equipment is available for a time slot
     */
    public function isAvailable(\Carbon\Carbon $from, \Carbon\Carbon $to, ?int $excludeReservationId = null): bool
    {
        $availableCount = $this->available_quantity;
        
        // Count overlapping reservations
        $overlappingReservations = $this->reservations()
            ->whereIn('status', ['reserved', 'in_use'])
            ->where(function($q) use ($from, $to) {
                $q->where(function($subQ) use ($from, $to) {
                    // Reservation starts before requested end AND ends after requested start
                    $subQ->where('reserved_from', '<', $to)
                         ->where('reserved_to', '>', $from);
                });
            });

        if ($excludeReservationId) {
            $overlappingReservations->where('id', '!=', $excludeReservationId);
        }

        $reservedCount = $overlappingReservations->count();
        
        return ($this->available_quantity - $reservedCount) > 0;
    }

    /**
     * Get available quantity for a time slot
     */
    public function getAvailableQuantity(\Carbon\Carbon $from, \Carbon\Carbon $to): int
    {
        $overlappingReservations = $this->reservations()
            ->whereIn('status', ['reserved', 'in_use'])
            ->where(function($q) use ($from, $to) {
                $q->where(function($subQ) use ($from, $to) {
                    $subQ->where('reserved_from', '<', $to)
                         ->where('reserved_to', '>', $from);
                });
            })
            ->count();

        return max(0, $this->available_quantity - $overlappingReservations);
    }
}

