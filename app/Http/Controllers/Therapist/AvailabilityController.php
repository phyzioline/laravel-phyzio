<?php

namespace App\Http\Controllers\Therapist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TherapistProfile;

class AvailabilityController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        $profile = TherapistProfile::where('user_id', $user->id)->firstOrFail();
        
        $availability = $profile->working_hours ?? []; 
        
        return view('therapist.availability.edit', compact('availability'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'availability' => 'array', 
        ]);
        
        $user = auth()->user();
        $profile = TherapistProfile::where('user_id', $user->id)->firstOrFail();
        
        $profile->working_hours = $request->availability;
        $profile->save();

        return redirect()->back()->with('success', 'Availability updated successfully.');
    }
}
