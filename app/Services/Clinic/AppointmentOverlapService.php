<?php

namespace App\Services\Clinic;

use App\Models\ClinicAppointment;
use Carbon\Carbon;

class AppointmentOverlapService
{
    /**
     * Check if a doctor has an overlapping appointment
     * 
     * @param int $doctorId
     * @param Carbon $startTime
     * @param int $durationMinutes
     * @param int|null $excludeAppointmentId Exclude this appointment (for updates)
     * @return bool
     */
    public function hasDoctorOverlap(int $doctorId, Carbon $startTime, int $durationMinutes, ?int $excludeAppointmentId = null): bool
    {
        if (!$doctorId) {
            return false; // No doctor assigned, no overlap
        }

        $endTime = $startTime->copy()->addMinutes($durationMinutes);

        $query = ClinicAppointment::where('doctor_id', $doctorId)
            ->whereIn('status', ['scheduled', 'confirmed', 'in_progress'])
            ->where(function($q) use ($startTime, $endTime) {
                // Check if existing appointment overlaps with new appointment
                $q->where(function($subQ) use ($startTime, $endTime) {
                    // Existing appointment starts before new appointment ends
                    // AND existing appointment ends after new appointment starts
                    $subQ->where('appointment_date', '<', $endTime)
                         ->whereRaw('DATE_ADD(appointment_date, INTERVAL duration_minutes MINUTE) > ?', [$startTime]);
                });
            });

        if ($excludeAppointmentId) {
            $query->where('id', '!=', $excludeAppointmentId);
        }

        return $query->exists();
    }

    /**
     * Check if a patient has an overlapping appointment
     * 
     * @param int $patientId
     * @param Carbon $startTime
     * @param int $durationMinutes
     * @param int|null $excludeAppointmentId Exclude this appointment (for updates)
     * @return bool
     */
    public function hasPatientOverlap(int $patientId, Carbon $startTime, int $durationMinutes, ?int $excludeAppointmentId = null): bool
    {
        $endTime = $startTime->copy()->addMinutes($durationMinutes);

        $query = ClinicAppointment::where('patient_id', $patientId)
            ->whereIn('status', ['scheduled', 'confirmed', 'in_progress'])
            ->where(function($q) use ($startTime, $endTime) {
                // Check if existing appointment overlaps with new appointment
                $q->where(function($subQ) use ($startTime, $endTime) {
                    // Existing appointment starts before new appointment ends
                    // AND existing appointment ends after new appointment starts
                    $subQ->where('appointment_date', '<', $endTime)
                         ->whereRaw('DATE_ADD(appointment_date, INTERVAL duration_minutes MINUTE) > ?', [$startTime]);
                });
            });

        if ($excludeAppointmentId) {
            $query->where('id', '!=', $excludeAppointmentId);
        }

        return $query->exists();
    }

    /**
     * Check for overlaps and return detailed information
     * 
     * @param int|null $doctorId
     * @param int $patientId
     * @param Carbon $startTime
     * @param int $durationMinutes
     * @param int|null $excludeAppointmentId
     * @return array ['has_overlap' => bool, 'doctor_overlap' => bool, 'patient_overlap' => bool, 'conflicts' => []]
     */
    public function checkOverlaps(?int $doctorId, int $patientId, Carbon $startTime, int $durationMinutes, ?int $excludeAppointmentId = null): array
    {
        $doctorOverlap = false;
        $patientOverlap = false;
        $conflicts = [];

        if ($doctorId) {
            $doctorOverlap = $this->hasDoctorOverlap($doctorId, $startTime, $durationMinutes, $excludeAppointmentId);
            if ($doctorOverlap) {
                $conflicts[] = [
                    'type' => 'doctor',
                    'message' => 'Doctor already has an appointment at this time.'
                ];
            }
        }

        $patientOverlap = $this->hasPatientOverlap($patientId, $startTime, $durationMinutes, $excludeAppointmentId);
        if ($patientOverlap) {
            $conflicts[] = [
                'type' => 'patient',
                'message' => 'Patient already has an appointment at this time.'
            ];
        }

        return [
            'has_overlap' => $doctorOverlap || $patientOverlap,
            'doctor_overlap' => $doctorOverlap,
            'patient_overlap' => $patientOverlap,
            'conflicts' => $conflicts
        ];
    }

    /**
     * Get available time slots for a doctor on a given date
     * 
     * @param int $doctorId
     * @param Carbon $date
     * @param int $slotDurationMinutes
     * @param string $startTime
     * @param string $endTime
     * @return array
     */
    public function getAvailableSlots(int $doctorId, Carbon $date, int $slotDurationMinutes = 60, string $startTime = '09:00', string $endTime = '17:00'): array
    {
        $availableSlots = [];
        $currentTime = Carbon::parse($date->format('Y-m-d') . ' ' . $startTime);
        $endDateTime = Carbon::parse($date->format('Y-m-d') . ' ' . $endTime);

        // Get existing appointments for this doctor on this date
        $existingAppointments = ClinicAppointment::where('doctor_id', $doctorId)
            ->whereDate('appointment_date', $date)
            ->whereIn('status', ['scheduled', 'confirmed', 'in_progress'])
            ->orderBy('appointment_date')
            ->get();

        $blockedSlots = [];
        foreach ($existingAppointments as $appointment) {
            $apptStart = Carbon::parse($appointment->appointment_date);
            $apptEnd = $apptStart->copy()->addMinutes($appointment->duration_minutes);
            $blockedSlots[] = [
                'start' => $apptStart,
                'end' => $apptEnd
            ];
        }

        while ($currentTime->copy()->addMinutes($slotDurationMinutes)->lte($endDateTime)) {
            $slotEnd = $currentTime->copy()->addMinutes($slotDurationMinutes);
            $isAvailable = true;

            // Check if this slot overlaps with any blocked slot
            foreach ($blockedSlots as $blocked) {
                if ($currentTime->lt($blocked['end']) && $slotEnd->gt($blocked['start'])) {
                    $isAvailable = false;
                    break;
                }
            }

            if ($isAvailable) {
                $availableSlots[] = $currentTime->format('H:i');
            }

            $currentTime->addMinutes($slotDurationMinutes);
        }

        return $availableSlots;
    }
}

