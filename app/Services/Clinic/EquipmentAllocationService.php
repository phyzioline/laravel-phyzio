<?php

namespace App\Services\Clinic;

use App\Models\EquipmentInventory;
use App\Models\EquipmentReservation;
use App\Models\ClinicAppointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EquipmentAllocationService
{
    /**
     * Check equipment availability and reserve if available
     * 
     * @param ClinicAppointment $appointment
     * @param array $equipmentTypes Array of equipment types needed
     * @return array ['success' => bool, 'reservations' => [], 'errors' => []]
     */
    public function allocateEquipment(ClinicAppointment $appointment, array $equipmentTypes): array
    {
        $reservations = [];
        $errors = [];
        $appointmentStart = Carbon::parse($appointment->appointment_date);
        $appointmentEnd = $appointmentStart->copy()->addMinutes($appointment->duration_minutes);

        try {
            DB::beginTransaction();

            foreach ($equipmentTypes as $equipmentType) {
                // Find available equipment of this type
                $equipment = EquipmentInventory::where('clinic_id', $appointment->clinic_id)
                    ->where('type', $equipmentType)
                    ->where('is_active', true)
                    ->get()
                    ->first(function($eq) use ($appointmentStart, $appointmentEnd) {
                        return $eq->isAvailable($appointmentStart, $appointmentEnd);
                    });

                if (!$equipment) {
                    $errors[] = "No available {$equipmentType} equipment for this time slot.";
                    continue;
                }

                // Create reservation
                $reservation = EquipmentReservation::create([
                    'equipment_id' => $equipment->id,
                    'appointment_id' => $appointment->id,
                    'clinic_id' => $appointment->clinic_id,
                    'reserved_from' => $appointmentStart,
                    'reserved_to' => $appointmentEnd,
                    'status' => 'reserved',
                    'notes' => "Auto-reserved for appointment #{$appointment->id}"
                ]);

                $reservations[] = $reservation;
            }

            if (!empty($errors) && empty($reservations)) {
                DB::rollBack();
                return [
                    'success' => false,
                    'reservations' => [],
                    'errors' => $errors
                ];
            }

            DB::commit();

            return [
                'success' => true,
                'reservations' => $reservations,
                'errors' => $errors
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Equipment allocation failed', [
                'appointment_id' => $appointment->id,
                'equipment_types' => $equipmentTypes,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'reservations' => [],
                'errors' => ['Failed to allocate equipment: ' . $e->getMessage()]
            ];
        }
    }

    /**
     * Check equipment availability without reserving
     * 
     * @param int $clinicId
     * @param array $equipmentTypes
     * @param Carbon $from
     * @param Carbon $to
     * @return array ['available' => bool, 'equipment_status' => []]
     */
    public function checkAvailability(int $clinicId, array $equipmentTypes, Carbon $from, Carbon $to): array
    {
        $equipmentStatus = [];

        foreach ($equipmentTypes as $type) {
            $equipment = EquipmentInventory::where('clinic_id', $clinicId)
                ->where('type', $type)
                ->where('is_active', true)
                ->first();

            if (!$equipment) {
                $equipmentStatus[$type] = [
                    'available' => false,
                    'message' => "No {$type} equipment found in inventory."
                ];
                continue;
            }

            $availableQuantity = $equipment->getAvailableQuantity($from, $to);
            $equipmentStatus[$type] = [
                'available' => $availableQuantity > 0,
                'available_quantity' => $availableQuantity,
                'total_quantity' => $equipment->quantity,
                'message' => $availableQuantity > 0 
                    ? "{$availableQuantity} {$type} equipment available."
                    : "No {$type} equipment available for this time slot."
            ];
        }

        $allAvailable = collect($equipmentStatus)->every(fn($status) => $status['available']);

        return [
            'available' => $allAvailable,
            'equipment_status' => $equipmentStatus
        ];
    }

    /**
     * Release equipment reservations for an appointment
     * 
     * @param ClinicAppointment $appointment
     * @return bool
     */
    public function releaseEquipment(ClinicAppointment $appointment): bool
    {
        try {
            EquipmentReservation::where('appointment_id', $appointment->id)
                ->whereIn('status', ['reserved', 'in_use'])
                ->update(['status' => 'returned']);

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to release equipment', [
                'appointment_id' => $appointment->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}

