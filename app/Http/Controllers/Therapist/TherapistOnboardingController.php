<?php

namespace App\Http\Controllers\Therapist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\TherapistProfile;
use App\Models\Location\Governorate;
use App\Models\Location\City;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TherapistOnboardingController extends Controller
{
    public function showStep1() // Personal & Professional
    {
        $therapist = TherapistProfile::firstOrCreate(['user_id' => Auth::id()]);
        return view('web.therapist.onboarding.step1_profile', compact('therapist'));
    }

    public function postStep1(Request $request)
    {
        $request->validate([
            'dob' => 'required|date',
            'gender' => 'required|in:male,female',
            'specialty' => 'required|string',
            'professional_level' => 'required|string',
            'license_number' => 'required|string',
        ]);

        $therapist = TherapistProfile::where('user_id', Auth::id())->first();
        
        // Handle File Uploads (Photo, License, Degree) - simplified for brevity
        if ($request->hasFile('profile_photo')) {
             $path = $request->file('profile_photo')->store('therapists/photos', 'public');
             $therapist->profile_photo = $path;
        }
        
        $therapist->date_of_birth = $request->dob;
        $therapist->gender = $request->gender;
        $therapist->national_id = $request->national_id;
        $therapist->specialization = $request->specialty;
        $therapist->professional_level = $request->professional_level;
        $therapist->license_number = $request->license_number;
        $therapist->license_issuing_authority = $request->license_authority;
        $therapist->years_experience = $request->years_experience;
        $therapist->save();

        return redirect()->route('therapist.onboarding.step2');
    }

    public function showStep2() // Activity Settings
    {
        $therapist = TherapistProfile::where('user_id', Auth::id())->firstOrFail();
        return view('web.therapist.onboarding.step2_activity', compact('therapist'));
    }

    public function postStep2(Request $request)
    {
        // Save services and notifications
        $therapist = TherapistProfile::where('user_id', Auth::id())->first();
        
        $therapist->bio = $request->bio;
        $therapist->notification_preferences = json_encode($request->notifications);
        $therapist->profile_visibility = $request->visibility;
        $therapist->save();

        // Save Services Logic here (simplified placeholder)
        // foreach($request->services as $service) { ... }

        return redirect()->route('therapist.onboarding.step3');
    }

    public function showStep3() // Treatment Workflow
    {
        $therapist = TherapistProfile::where('user_id', Auth::id())->firstOrFail();
        return view('web.therapist.onboarding.step3_treatment', compact('therapist'));
    }

    public function postStep3(Request $request)
    {
        $therapist = TherapistProfile::where('user_id', Auth::id())->first();
        $therapist->evaluation_template = json_encode($request->evaluation);
        $therapist->treatment_plan_template = json_encode($request->treatment_plan);
        $therapist->session_note_template = json_encode($request->session_note);
        $therapist->save();

        return redirect()->route('therapist.onboarding.step4');
    }

    public function showStep4() // Calendar
    {
        $therapist = TherapistProfile::where('user_id', Auth::id())->firstOrFail();
        return view('web.therapist.onboarding.step4_calendar', compact('therapist'));
    }

    public function postStep4(Request $request)
    {
        $therapist = TherapistProfile::where('user_id', Auth::id())->first();
        $therapist->working_hours = json_encode($request->schedule);
        $therapist->calendar_settings = json_encode($request->calendar);
        $therapist->max_sessions_per_day = $request->max_sessions;
        $therapist->break_hours = $request->break_hours;
        $therapist->save();

        return redirect()->route('therapist.onboarding.step5');
    }
    
    public function showStep5() // Payments
    {
        $therapist = TherapistProfile::where('user_id', Auth::id())->firstOrFail();
        return view('web.therapist.onboarding.step5_payments', compact('therapist'));
    }

    public function postStep5(Request $request)
    {
        $therapist = TherapistProfile::where('user_id', Auth::id())->first();
        $therapist->bank_account_number = $request->bank_account_number;
        $therapist->bank_name = $request->bank_name;
        $therapist->bank_account_name = $request->account_holder_name;
        $therapist->mobile_wallet_number = $request->wallet_number;
        $therapist->payment_frequency = $request->payment_frequency;
        $therapist->save();

        return redirect()->route('therapist.onboarding.step6');
    }
    
    public function showStep6() // Confirmation
    {
        $therapist = TherapistProfile::where('user_id', Auth::id())->firstOrFail();
        return view('web.therapist.onboarding.step6_confirmation', compact('therapist'));
    }
    
    public function submit(Request $request)
    {
        $therapist = TherapistProfile::where('user_id', Auth::id())->first();
        $therapist->status = 'pending'; // Submit for approval
        $therapist->save();
        
        return redirect()->route('therapist.dashboard');
    }
}
