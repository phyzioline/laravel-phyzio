<?php

namespace App\Http\Controllers\Therapist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        $profile = \App\Models\TherapistProfile::where('user_id', $user->id)->firstOrCreate(['user_id' => $user->id]);
        $locations = config('locations');
        return view('web.therapist.profile', compact('user', 'profile', 'locations'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'specialization' => 'required|string',
            'bio' => 'nullable|string',
            'home_visit_rate' => 'required|numeric',
            'available_areas' => 'nullable|array',
            'profile_image' => 'nullable|image',
            'bank_name' => 'nullable|string',
            'bank_account_name' => 'nullable|string',
            'iban' => 'nullable|string',
            'swift_code' => 'nullable|string',
        ]);

        // Update User Table
        $user->update($request->only(['name', 'phone']));

        // Update Therapist Profile Table
        $profile = \App\Models\TherapistProfile::where('user_id', $user->id)->first();
        
        $data = $request->only(['specialization', 'bio', 'home_visit_rate', 'available_areas', 'bank_name', 'bank_account_name', 'iban', 'swift_code']);
        
        if ($request->hasFile('profile_image')) {
            $path = $request->file('profile_image')->store('therapists', 'public');
            $data['profile_image'] = $path;
        }

        $profile->update($data);

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}
