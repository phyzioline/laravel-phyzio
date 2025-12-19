<?php

namespace App\Http\Controllers\Therapist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Fetch Home Visits (Booked Slots)
        $appointments = \App\Models\HomeVisit::where('therapist_id', $user->id)
            ->whereDate('scheduled_at', '>=', now()->startOfMonth())
            ->whereDate('scheduled_at', '<=', now()->endOfMonth())
            ->get();
            
        // Fetch Availability Schedules
        $schedules = \App\Models\TherapistSchedule::where('therapist_id', $user->id)
            ->where('is_active', true)
            ->get();
            
        // Prepare events for calendar (simplified for now, full implementation would separate this)
        $events = [];
        
        foreach($appointments as $appt) {
            $events[] = [
                'title' => 'Booked: ' . ($appt->patient->name ?? 'Patient'),
                'start' => $appt->scheduled_at ? $appt->scheduled_at->toIso8601String() : now()->toIso8601String(),
                'color' => '#dc3545' // Red
            ];
        }
        
        // Mocking slots based on schedule for visualization would be complex here without a full calendar library integration
        // passing $schedules to view to loop through
        
        // Calculate Stats
        $availableSlots = $schedules->sum(function($s) {
             $start = \Carbon\Carbon::parse($s->start_time);
             $end = \Carbon\Carbon::parse($s->end_time);
             return floor($start->diffInMinutes($end) / ($s->slot_duration ?: 30));
        }) * 4; // Approx 4 weeks in a month
        
        $bookedSlots = $appointments->count();
        $blockedSlots = 0; // Add blocked logic if needed
        $utilizationRate = $availableSlots > 0 ? round(($bookedSlots / $availableSlots) * 100) : 0;

        return view('web.therapist.schedule.index', compact('events', 'schedules', 'availableSlots', 'bookedSlots', 'blockedSlots', 'utilizationRate'));
    }
}
