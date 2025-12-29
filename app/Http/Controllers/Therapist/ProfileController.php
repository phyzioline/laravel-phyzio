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

        // Ensure Therapist Profile exists (firstOrCreate to prevent null errors)
        $profile = \App\Models\TherapistProfile::where('user_id', $user->id)->firstOrCreate([
            'user_id' => $user->id
        ]);
        
        $data = $request->only(['specialization', 'bio', 'home_visit_rate', 'available_areas', 'bank_name', 'bank_account_name', 'iban', 'swift_code']);
        
        if ($request->hasFile('profile_image')) {
            // Validate image
            $request->validate([
                'profile_image' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
            ]);
            
            // Delete old image if exists (handle both storage path and relative path)
            if ($user->image) {
                // If path starts with 'storage/', it's old format, try to delete from public
                if (strpos($user->image, 'storage/') === 0) {
                    $oldImagePath = public_path($user->image);
                    if (file_exists($oldImagePath)) {
                        @unlink($oldImagePath);
                    }
                }
                // Try storage path (relative path format)
                $oldStoragePath = storage_path('app/public/' . $user->image);
                if (file_exists($oldStoragePath)) {
                    @unlink($oldStoragePath);
                }
            }
            
            // Delete old profile photo if exists
            if ($profile->profile_photo) {
                // If path starts with 'storage/', it's old format
                if (strpos($profile->profile_photo, 'storage/') === 0) {
                    $oldProfilePhotoPath = public_path($profile->profile_photo);
                    if (file_exists($oldProfilePhotoPath)) {
                        @unlink($oldProfilePhotoPath);
                    }
                }
                // Try storage path (relative path format)
                $oldProfilePhotoStorage = storage_path('app/public/' . $profile->profile_photo);
                if (file_exists($oldProfilePhotoStorage)) {
                    @unlink($oldProfilePhotoStorage);
                }
            }
            
            // Store new image
            $path = $request->file('profile_image')->store('therapists/photos', 'public');
            // Store just the relative path (e.g., 'therapists/photos/filename.jpg')
            // This will work with Storage::url() in views
            
            // Update user image - store relative path
            $user->update(['image' => $path]);
            
            // Update therapist profile with profile_photo - store relative path
            $profile->update([
                'profile_photo' => $path
            ]);
        }

        // Update profile data (preserve existing status if set)
        try {
            $profile->update($data);
            
            // Note: We don't change verification_status, profile_visibility, or status here
            // These should be managed by admin verification process
            // However, if profile was just created and user is already verified, ensure status is set
            if (!$profile->status && $user->verification_status === 'approved' && $user->profile_visibility === 'visible') {
                $profile->update(['status' => 'approved']);
            }
            
            // Refresh the profile to ensure all data is loaded
            $profile->refresh();
            
            return redirect()->back()->with('success', __('Profile updated successfully.'));
        } catch (\Exception $e) {
            \Log::error('Profile update error: ' . $e->getMessage());
            return redirect()->back()->with('error', __('Failed to update profile. Please try again.'));
        }
    }
}
