<?php

namespace App\Http\Controllers\Therapist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    public function edit()
    {
        return view('web.therapist.availability');
    }

    public function update(Request $request)
    {
        // Logic to update availability will go here
        return redirect()->back()->with('success', 'Availability updated successfully.');
    }
}
