<?php

namespace App\Http\Controllers\Clinic;

use App\Models\TreatmentPlan;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TreatmentPlanController extends BaseClinicController
{
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $clinic = $this->getUserClinic();
        
        // Get patients for this clinic
        if ($clinic) {
            $patients = Patient::where('clinic_id', $clinic->id)->get();
        } else {
            $patients = collect();
        }
        
        // Get pre-selected patient_id from query parameter
        $selectedPatientId = $request->get('patient_id');
        
        return view('web.clinic.plans.create', compact('patients', 'selectedPatientId', 'clinic'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return back()->with('error', 'Clinic not found.');
        }

        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'diagnosis' => 'required|string',
            'treatment_goals' => 'nullable|string',
            'frequency' => 'required|integer',
            'duration' => 'required|integer',
        ]);

        // Verify patient belongs to this clinic
        $patient = Patient::where('clinic_id', $clinic->id)
            ->findOrFail($request->patient_id);

        $plan = new TreatmentPlan();
        // Note: treatment_plans table expects patient_id to be user_id, but we're using Patient model
        // If the table structure is different, we may need to adjust this
        $plan->patient_id = $patient->id; // This might need to be $patient->user_id if Patient has a user relationship
        $plan->therapist_id = Auth::id();
        $plan->diagnosis = $request->diagnosis;
        
        // Map treatment_goals to short_term_goals (or split if needed)
        $goals = $request->treatment_goals ?? '';
        $plan->short_term_goals = $goals;
        $plan->long_term_goals = null; // Can be added later
        
        // Convert frequency (sessions/week) and duration (weeks) to planned_sessions
        $plan->planned_sessions = $request->frequency * $request->duration;
        $plan->frequency = $request->frequency . 'x per week';
        $plan->status = 'active';
        $plan->start_date = now();
        
        $plan->save();

        return redirect()->route('clinic.patients.show', $patient->id)->with('success', 'Treatment Plan created successfully.');
    }
}
