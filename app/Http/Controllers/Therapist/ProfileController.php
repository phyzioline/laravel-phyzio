<?php

namespace App\Http\Controllers\Therapist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TherapistProfile;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
        // Assuming relationship 'therapistProfile' exists on User model, or we query it directly
        $profile = AbstractProfileController::getProfile($user->id) ?? new TherapistProfile(['user_id' => $user->id]);
        
        // Let's check how profile is retrieved. Based on previous conv, it might be via user->therapistProfile if relation defined.
        // For safety, I'll query it if relation not sure.
        $profile = TherapistProfile::where('user_id', $user->id)->first();
        
        return view('therapist.profile.edit', compact('user', 'profile'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'specialization' => 'required|string',
            'bio' => 'nullable|string',
            'home_visit_rate' => 'required|numeric|min:0',
            // Add other fields as necessary
        ]);

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        TherapistProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'specialization' => $request->specialization,
                'bio' => $request->bio,
                'home_visit_rate' => $request->home_visit_rate,
                // Add other fields
            ]
        );

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}
