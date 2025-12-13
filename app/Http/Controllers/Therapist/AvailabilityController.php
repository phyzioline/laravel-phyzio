<?php

namespace App\Http\Controllers\Therapist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function edit()
    {
        // Fetch existing schedules for the calendar
        $schedules = \App\Models\TherapistSchedule::where('therapist_id', auth()->id())
            ->where('is_active', true)
            ->get();
            
        // Calculate real stats
        $totalSchedules = $schedules->count();
        $availableSlots = $schedules->sum(function($schedule) {
            $start = \Carbon\Carbon::parse($schedule->start_time);
            $end = \Carbon\Carbon::parse($schedule->end_time);
            return $start->diffInMinutes($end) / ($schedule->slot_duration ?: 30);
        });
        
        // Blocked slots could be appointments
        $blockedSlots = \App\Models\Appointment::where('therapist_id', auth()->id())
            ->whereDate('appointment_date', '>=', now())
            ->count();
            
        $utilizationRate = $availableSlots > 0 ? round(($blockedSlots / $availableSlots) * 100) : 0;

        return view('web.therapist.availability', compact('schedules', 'availableSlots', 'blockedSlots', 'utilizationRate'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'days' => 'required|array',
            'start_time' => 'required',
            'end_time' => 'required',
            'slot_duration' => 'required|integer',
            'break_duration' => 'nullable|integer',
        ]);

        $user = auth()->user();

        // Clear overlapping or existing schedules in this range if needed, or just append. 
        // For simplicity, we just create new rules.
        
        foreach ($request->days as $day) {
            \App\Models\TherapistSchedule::create([
                'therapist_id' => $user->id,
                'day_of_week' => $day,
                'start_time' => $request->start_time,
                'end_time' => $request->end_time,
                'slot_duration' => $request->slot_duration,
                'break_duration' => $request->break_duration ?? 0,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'is_active' => true
            ]);
        }

        return redirect()->back()->with('message', ['type' => 'success', 'text' => 'Availability schedule updated successfully!']);
    }
}
