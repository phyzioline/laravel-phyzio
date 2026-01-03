<?php

namespace App\Services\Clinic;

use App\Models\ClinicAppointment;
use App\Models\BookingSlot;
use App\Models\SlotDoctorAssignment;
use App\Models\DoctorHourlyRate;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class IntensiveSessionService
{
    /**
     * Create intensive session with slots
     */
    public function createIntensiveSession(array $appointmentData, int $totalHours): ClinicAppointment
    {
        $appointment = ClinicAppointment::create($appointmentData);
        
        // Create slots (1 slot per hour)
        $startTime = Carbon::parse($appointment->appointment_date);
        
        for ($i = 1; $i <= $totalHours; $i++) {
            $slotStart = $startTime->copy()->addHours($i - 1);
            $slotEnd = $slotStart->copy()->addHour();
            
            BookingSlot::create([
                'appointment_id' => $appointment->id,
                'clinic_id' => $appointment->clinic_id,
                'slot_number' => $i,
                'slot_start_time' => $slotStart,
                'slot_end_time' => $slotEnd,
                'slot_duration_minutes' => 60,
                'status' => 'pending',
            ]);
        }
        
        return $appointment;
    }

    /**
     * Assign doctor to a slot
     */
    public function assignDoctorToSlot(int $slotId, int $doctorId, ?int $assignedBy = null): SlotDoctorAssignment
    {
        $slot = BookingSlot::findOrFail($slotId);
        
        // Check for conflicts
        $conflicts = $this->checkSlotConflicts($slot, $doctorId);
        if ($conflicts['has_conflict']) {
            throw new \Exception('Doctor has scheduling conflict: ' . implode(', ', $conflicts['messages']));
        }
        
        // Check doctor's hourly limits
        $rate = \App\Models\DoctorHourlyRate::where('clinic_id', $slot->clinic_id)
            ->where('doctor_id', $doctorId)
            ->where('is_active', true)
            ->first();
        
        if ($rate) {
            if (!$rate->canWorkMoreHoursToday()) {
                throw new \Exception('Doctor has reached daily hours limit');
            }
            if (!$rate->canWorkMoreHoursThisWeek()) {
                throw new \Exception('Doctor has reached weekly hours limit');
            }
        }
        
        // Create assignment
        $assignment = SlotDoctorAssignment::create([
            'slot_id' => $slotId,
            'appointment_id' => $slot->appointment_id,
            'doctor_id' => $doctorId,
            'clinic_id' => $slot->clinic_id,
            'assigned_at' => now(),
            'assigned_by' => $assignedBy,
            'status' => 'assigned',
        ]);
        
        // Update slot status
        $slot->update(['status' => 'assigned']);
        
        // Create work log entry
        $assignment->createWorkLog();
        
        return $assignment;
    }

    /**
     * Check for scheduling conflicts
     */
    public function checkSlotConflicts(BookingSlot $slot, int $doctorId): array
    {
        $conflicts = [];
        $hasConflict = false;
        
        $slotStart = $slot->slot_start_time->copy();
        $slotEnd = $slot->slot_end_time->copy();
        
        // Check if doctor has other appointments during this time
        $overlappingAppointments = \App\Models\ClinicAppointment::where('doctor_id', $doctorId)
            ->where('id', '!=', $slot->appointment_id)
            ->where(function($q) use ($slotStart, $slotEnd) {
                $q->where(function($q2) use ($slotStart, $slotEnd) {
                    // Appointment starts during slot
                    $q2->whereBetween('appointment_date', [$slotStart, $slotEnd->copy()->subSecond()])
                       // Or appointment ends during slot
                       ->orWhereRaw('DATE_ADD(appointment_date, INTERVAL duration_minutes MINUTE) BETWEEN ? AND ?', [$slotStart->copy()->addSecond(), $slotEnd])
                       // Or appointment completely contains slot
                       ->orWhere(function($q3) use ($slotStart, $slotEnd) {
                           $q3->where('appointment_date', '<=', $slotStart)
                              ->whereRaw('DATE_ADD(appointment_date, INTERVAL duration_minutes MINUTE) >= ?', [$slotEnd]);
                       });
                });
            })
            ->get();
        
        if ($overlappingAppointments->count() > 0) {
            $hasConflict = true;
            $conflicts[] = 'Doctor has overlapping appointment';
        }
        
        // Check if doctor has other slot assignments during this time
        $overlappingSlots = SlotDoctorAssignment::where('doctor_id', $doctorId)
            ->where('slot_id', '!=', $slot->id)
            ->whereHas('slot', function($q) use ($slotStart, $slotEnd) {
                $q->where(function($q2) use ($slotStart, $slotEnd) {
                    // Slot starts during our slot
                    $q2->whereBetween('slot_start_time', [$slotStart, $slotEnd->copy()->subSecond()])
                       // Or slot ends during our slot
                       ->orWhereBetween('slot_end_time', [$slotStart->copy()->addSecond(), $slotEnd])
                       // Or slot completely contains our slot
                       ->orWhere(function($q3) use ($slotStart, $slotEnd) {
                           $q3->where('slot_start_time', '<=', $slotStart)
                              ->where('slot_end_time', '>=', $slotEnd);
                       });
                });
            })
            ->get();
        
        if ($overlappingSlots->count() > 0) {
            $hasConflict = true;
            $conflicts[] = 'Doctor has overlapping slot assignment';
        }
        
        return [
            'has_conflict' => $hasConflict,
            'messages' => $conflicts
        ];
    }

    /**
     * Get available doctors for a slot
     */
    public function getAvailableDoctors(int $clinicId, BookingSlot $slot, ?string $specialty = null): array
    {
        $doctors = \App\Models\User::whereHas('clinicStaff', function($q) use ($clinicId) {
            $q->where('clinic_id', $clinicId)
              ->whereIn('role', ['therapist', 'doctor'])
              ->where('is_active', true);
        })
        ->whereIn('type', ['therapist', 'doctor'])
        ->get();
        
        $available = [];
        
        foreach ($doctors as $doctor) {
            // Check hourly rate exists
            $rate = \App\Models\DoctorHourlyRate::where('clinic_id', $clinicId)
                ->where('doctor_id', $doctor->id)
                ->where('is_active', true)
                ->first();
            
            if (!$rate) {
                continue; // Skip doctors without hourly rate
            }
            
            // Check specialty match
            if ($specialty && $rate->allowed_specialties && !in_array($specialty, $rate->allowed_specialties)) {
                continue;
            }
            
            // Check conflicts
            $conflicts = $this->checkSlotConflicts($slot, $doctor->id);
            if ($conflicts['has_conflict']) {
                continue;
            }
            
            // Check hours limits
            if (!$rate->canWorkMoreHoursToday() || !$rate->canWorkMoreHoursThisWeek()) {
                continue;
            }
            
            $available[] = [
                'id' => $doctor->id,
                'name' => $doctor->name,
                'hourly_rate' => $rate->hourly_rate,
                'specialty' => $doctor->therapistProfile->specialization ?? null,
            ];
        }
        
        return $available;
    }
}

