<?php

namespace App\Http\Controllers\Therapist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    public function index()
    {
        // Mock slots/schedule data
        $slots = []; // Populate with calendar events format later
        return view('web.therapist.schedule.index', compact('slots'));
    }
}
