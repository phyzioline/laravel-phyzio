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
        
        // Calculate real rating from reviews (if reviews table exists) or use profile rating
        $rating = $profile->rating ?? 0;
        $totalReviews = $profile->total_reviews ?? 0;
        
        // Calculate total unique patients
        $totalPatients = \App\Models\HomeVisit::where('therapist_id', $user->id)
            ->distinct('patient_id')
            ->count('patient_id');
        
        return view('web.therapist.profile', compact('user', 'profile', 'locations', 'rating', 'totalReviews', 'totalPatients'));
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
            // Validate image
            $request->validate([
                'profile_image' => 'image|mimes:jpeg,png,jpg,gif|max:5120', // 5MB max
            ]);
            
            // Delete old image if exists
            if ($user->image) {
                $oldImagePath = public_path($user->image);
                if (file_exists($oldImagePath)) {
                    @unlink($oldImagePath);
                }
                // Also try storage path
                $oldStoragePath = storage_path('app/public/' . str_replace('storage/', '', $user->image));
                if (file_exists($oldStoragePath)) {
                    @unlink($oldStoragePath);
                }
            }
            
            // Store new image
            $path = $request->file('profile_image')->store('profiles', 'public');
            $imagePath = 'storage/' . $path;
            
            // Update user image
            $user->update(['image' => $imagePath]);
            
            // Also update therapist profile if it has an image field
            if ($profile) {
                $profile->update(['profile_image' => $imagePath]);
            }
        }

        $profile->update($data);

        return redirect()->back()->with('success', 'Profile updated successfully.');
    }
}
