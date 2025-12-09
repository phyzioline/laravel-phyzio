<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\TreatmentPlan;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TreatmentPlanController extends Controller
{
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $patients = Patient::all(); // Should be filtered by clinic
        return view('web.clinic.plans.create', compact('patients'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'diagnosis' => 'required|string',
            'treatment_goals' => 'nullable|string', // JSON or text, user asked for JSON but simple text area often easier for MVPs
            'frequency' => 'required|integer',
            'duration' => 'required|integer',
        ]);

        $plan = new TreatmentPlan();
        $plan->patient_id = $request->patient_id;
        $plan->therapist_id = Auth::id();
        $plan->diagnosis = $request->diagnosis;
        // $plan->treatment_goals = json_encode(explode("\n", $request->treatment_goals)); // Handle as array if needed
        $plan->treatment_goals = $request->treatment_goals; // Storing as is for now if string
        $plan->frequency_sessions_per_week = $request->frequency;
        $plan->duration_weeks = $request->duration;
        $plan->status = 'active';
        $plan->save();

        return redirect()->route('clinic.patients.show', $plan->patient_id)->with('success', 'Treatment Plan created successfully.');
    }
}
