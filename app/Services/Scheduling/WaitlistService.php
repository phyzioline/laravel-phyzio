<?php

namespace App\Services\Scheduling;

use App\Models\Waitlist;
use App\Models\ClinicAppointment;
use App\Services\Clinic\AppointmentOverlapService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WaitlistService
{
    protected $overlapService;

    public function __construct(AppointmentOverlapService $overlapService)
    {
        $this->overlapService = $overlapService;
    }

    /**
     * Add patient to waitlist
     * 
     * @param array $data
     * @return Waitlist
     */
    public function addToWaitlist(array $data): Waitlist
    {
        return Waitlist::create([
            'clinic_id' => $data['clinic_id'],
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctor_id'] ?? null,
            'specialty' => $data['specialty'] ?? null,
            'visit_type' => $data['visit_type'] ?? null,
            'priority' => $data['priority'] ?? 'normal',
            'notes' => $data['notes'] ?? null,
            'preferred_start_date' => isset($data['preferred_start_date']) ? Carbon::parse($data['preferred_start_date']) : null,
            'preferred_end_date' => isset($data['preferred_end_date']) ? Carbon::parse($data['preferred_end_date']) : null,
            'preferred_times' => $data['preferred_times'] ?? null,
            'preferred_days' => $data['preferred_days'] ?? null,
            'status' => 'active'
        ]);
    }

    /**
     * Check waitlist for available slots and auto-book
     * 
     * @param int $clinicId
     * @param Carbon $appointmentDate
     * @param int $doctorId
     * @return int Number of appointments booked
     */
    public function checkAndBookFromWaitlist(int $clinicId, Carbon $appointmentDate, ?int $doctorId = null): int
    {
        $bookedCount = 0;

        // Get active waitlist entries matching criteria
        $waitlistEntries = Waitlist::where('clinic_id', $clinicId)
            ->where('status', 'active')
            ->where(function($q) use ($doctorId, $appointmentDate) {
                if ($doctorId) {
                    $q->where('doctor_id', $doctorId)
                      ->orWhereNull('doctor_id');
                }
                
                // Check if date is within preferred range
                $q->where(function($dateQ) use ($appointmentDate) {
                    $dateQ->whereNull('preferred_start_date')
                          ->orWhere('preferred_start_date', '<=', $appointmentDate);
                })
                ->where(function($dateQ) use ($appointmentDate) {
                    $dateQ->whereNull('preferred_end_date')
                          ->orWhere('preferred_end_date', '>=', $appointmentDate);
                });
            })
            ->orderBy('priority', 'desc') // Urgent first
            ->orderBy('created_at', 'asc') // Then by wait time
            ->get();

        foreach ($waitlistEntries as $entry) {
            // Check if slot is available
            $targetDoctorId = $entry->doctor_id ?? $doctorId;
            if (!$targetDoctorId) {
                continue;
            }

            // Get available slots for this doctor
            $availableSlots = $this->overlapService->getAvailableSlots(
                $targetDoctorId,
                $appointmentDate,
                60 // Default duration
            );

            if (!empty($availableSlots)) {
                // Try to book first available slot
                $slotTime = $availableSlots[0];
                $appointmentDateTime = Carbon::parse($appointmentDate->format('Y-m-d') . ' ' . $slotTime);

                // Check for overlap
                $overlapCheck = $this->overlapService->checkOverlaps(
                    $targetDoctorId,
                    $entry->patient_id,
                    $appointmentDateTime,
                    60
                );

                if (!$overlapCheck['has_overlap']) {
                    try {
                        // Create appointment
                        $appointment = ClinicAppointment::create([
                            'clinic_id' => $clinicId,
                            'patient_id' => $entry->patient_id,
                            'doctor_id' => $targetDoctorId,
                            'appointment_date' => $appointmentDateTime,
                            'duration_minutes' => 60,
                            'status' => 'scheduled',
                            'visit_type' => $entry->visit_type ?? 'followup',
                            'specialty' => $entry->specialty,
                            'location' => 'clinic'
                        ]);

                        // Mark waitlist entry as booked
                        $entry->markAsBooked();

                        // Send notification (would integrate with notification service)
                        // $this->notifyPatient($entry->patient, $appointment);

                        $bookedCount++;
                    } catch (\Exception $e) {
                        Log::error('Failed to auto-book from waitlist', [
                            'waitlist_id' => $entry->id,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            }
        }

        return $bookedCount;
    }

    /**
     * Get waitlist position for a patient
     * 
     * @param int $patientId
     * @param int $clinicId
     * @return int|null
     */
    public function getWaitlistPosition(int $patientId, int $clinicId): ?int
    {
        $entry = Waitlist::where('patient_id', $patientId)
            ->where('clinic_id', $clinicId)
            ->where('status', 'active')
            ->first();

        if (!$entry) {
            return null;
        }

        // Count entries ahead in queue
        $position = Waitlist::where('clinic_id', $clinicId)
            ->where('status', 'active')
            ->where(function($q) use ($entry) {
                // Higher priority first
                $priorityOrder = ['urgent' => 4, 'high' => 3, 'normal' => 2, 'low' => 1];
                $entryPriority = $priorityOrder[$entry->priority] ?? 2;
                
                $q->where(function($priorityQ) use ($entryPriority) {
                    foreach ($priorityOrder as $priority => $value) {
                        if ($value > $entryPriority) {
                            $priorityQ->orWhere('priority', $priority);
                        }
                    }
                })
                ->orWhere(function($dateQ) use ($entry) {
                    // Same priority, earlier created
                    $dateQ->where('priority', $entry->priority)
                          ->where('created_at', '<', $entry->created_at);
                });
            })
            ->count();

        return $position + 1;
    }
}

