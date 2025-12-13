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
            
        // Mock stats for the view (can be real later)
        $availableSlots = 32; 
        $blockedSlots = 6;
        $utilizationRate = 75;

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
