<?php

namespace App\Http\Controllers\Clinic;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DoctorScheduleController extends BaseClinicController
{
    /**
     * Show hourly schedule for the logged-in doctor
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $clinic = $this->getUserClinic($user);
        
        if (!$clinic) {
            return redirect()->route('clinic.dashboard')
                ->with('error', 'Clinic not found.');
        }
        
        // Verify user is a doctor/therapist assigned to this clinic
        $doctorStaff = \App\Models\ClinicStaff::where('clinic_id', $clinic->id)
            ->where('user_id', $user->id)
            ->whereIn('role', ['therapist', 'doctor'])
            ->where('is_active', true)
            ->first();
        
        if (!$doctorStaff) {
            return redirect()->route('clinic.dashboard')
                ->with('error', 'You are not assigned as a doctor to this clinic.');
        }
        
        // Get date range (default: today)
        $date = $request->get('date', now()->toDateString());
        $selectedDate = Carbon::parse($date);
        $startOfDay = $selectedDate->copy()->startOfDay();
        $endOfDay = $selectedDate->copy()->endOfDay();
        
        // Get regular appointments for this doctor
        $regularAppointments = \App\Models\ClinicAppointment::where('clinic_id', $clinic->id)
            ->where('doctor_id', $user->id)
            ->whereBetween('appointment_date', [$startOfDay, $endOfDay])
            ->with('patient')
            ->orderBy('appointment_date')
            ->get();
        
        // Get intensive session slot assignments for this doctor
        $slotAssignments = \App\Models\SlotDoctorAssignment::where('clinic_id', $clinic->id)
            ->where('doctor_id', $user->id)
            ->whereHas('slot', function($q) use ($startOfDay, $endOfDay) {
                $q->whereBetween('slot_start_time', [$startOfDay, $endOfDay]);
            })
            ->with(['slot.appointment.patient', 'appointment'])
            ->orderBy('slot.slot_start_time')
            ->get();
        
        // Combine and format for hourly view
        $hourlySchedule = [];
        
        // Add regular appointments
        foreach ($regularAppointments as $appt) {
            $hourlySchedule[] = [
                'type' => 'regular',
                'time' => $appt->appointment_date,
                'end_time' => $appt->appointment_date->copy()->addMinutes($appt->duration_minutes),
                'patient_name' => $appt->patient->first_name . ' ' . $appt->patient->last_name,
                'session_type' => 'Regular',
                'duration_minutes' => $appt->duration_minutes,
                'appointment' => $appt,
            ];
        }
        
        // Add intensive session slots
        foreach ($slotAssignments as $assignment) {
            $slot = $assignment->slot;
            $appointment = $assignment->appointment;
            $hourlySchedule[] = [
                'type' => 'intensive',
                'time' => $slot->slot_start_time,
                'end_time' => $slot->slot_end_time,
                'patient_name' => $appointment->patient->first_name . ' ' . $appointment->patient->last_name,
                'session_type' => 'Intensive',
                'duration_minutes' => $slot->slot_duration_minutes,
                'slot_number' => $slot->slot_number,
                'appointment' => $appointment,
                'slot' => $slot,
                'assignment' => $assignment,
            ];
        }
        
        // Sort by time
        usort($hourlySchedule, function($a, $b) {
            return $a['time']->timestamp <=> $b['time']->timestamp;
        });
        
        // Get today's work summary
        $todayWorkLogs = \App\Models\DoctorWorkLog::where('clinic_id', $clinic->id)
            ->where('doctor_id', $user->id)
            ->where('work_date', $selectedDate->toDateString())
            ->where('status', '!=', 'cancelled')
            ->get();
        
        $totalHoursToday = $todayWorkLogs->sum('hours_worked');
        $totalEarningsToday = $todayWorkLogs->sum('total_amount');
        
        // Get weekly summary
        $weekStart = $selectedDate->copy()->startOfWeek();
        $weekEnd = $selectedDate->copy()->endOfWeek();
        
        $weeklyWorkLogs = \App\Models\DoctorWorkLog::where('clinic_id', $clinic->id)
            ->where('doctor_id', $user->id)
            ->whereBetween('work_date', [$weekStart, $weekEnd])
            ->where('status', '!=', 'cancelled')
            ->get();
        
        $totalHoursThisWeek = $weeklyWorkLogs->sum('hours_worked');
        $totalEarningsThisWeek = $weeklyWorkLogs->sum('total_amount');
        
        // Get hourly rate
        $hourlyRate = \App\Models\DoctorHourlyRate::where('clinic_id', $clinic->id)
            ->where('doctor_id', $user->id)
            ->where('is_active', true)
            ->first();
        
        return view('web.clinic.doctor-schedule.index', compact(
            'clinic',
            'selectedDate',
            'hourlySchedule',
            'totalHoursToday',
            'totalEarningsToday',
            'totalHoursThisWeek',
            'totalEarningsThisWeek',
            'hourlyRate'
        ));
    }
}

