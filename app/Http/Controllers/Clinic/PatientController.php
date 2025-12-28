<?php

namespace App\Http\Controllers\Clinic;

use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends BaseClinicController
{
    /**
     * Display a listing of the patients.
     */
    public function index(Request $request)
    {
        $clinic = $this->getUserClinic();
        
        // Show empty state instead of redirecting
        if (!$clinic) {
            // Return empty paginated collection to avoid errors with ->links()
            $patients = new \Illuminate\Pagination\LengthAwarePaginator(
                collect([]),
                0,
                10,
                1,
                ['path' => request()->url(), 'query' => request()->query()]
            );
            return view('web.clinic.patients.index', compact('patients', 'clinic'));
        }

        $query = Patient::where('clinic_id', $clinic->id);

        if ($request->has('search') && $request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->has('status') && $request->filled('status')) {
            $query->where('status', $request->status);
        }

        $patients = $query->latest()->paginate(10)->withQueryString();

        return view('web.clinic.patients.index', compact('patients', 'clinic'));
    }

    /**
     * Show the form for creating a new patient.
     */
    public function create()
    {
        return view('web.clinic.patients.create');
    }

    /**
     * Store a newly created patient in storage.
     */
    public function store(Request $request)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return back()->with('error', 'Clinic not found. Please contact support.');
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
        ]);

        $patient = new Patient();
        $patient->clinic_id = $clinic->id; // CRITICAL: Set clinic_id
        $patient->first_name = $request->first_name;
        $patient->last_name = $request->last_name;
        $patient->phone = $request->phone;
        $patient->email = $request->email;
        $patient->date_of_birth = $request->dob;
        $patient->gender = $request->gender;
        $patient->address = $request->address;
        $patient->medical_history = $request->medical_history;
        $patient->insurance_provider = $request->insurance_provider;
        $patient->insurance_number = $request->insurance_number;
        $patient->save();

        return redirect()->route('clinic.patients.show', $patient->id)->with('success', 'Patient registered successfully.');
    }

    /**
     * Display the specified patient.
     */
    public function show($id)
    {
        $clinic = $this->getUserClinic();
        
        // Try to find patient even if no clinic (might be shared patient)
        try {
            if ($clinic) {
                $patient = Patient::where('clinic_id', $clinic->id)->findOrFail($id);
            } else {
                // If no clinic, try to find patient anyway (might be accessible)
                $patient = Patient::findOrFail($id);
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // Patient not found - show empty state
            $patient = null;
            $appointments = collect();
            $treatmentPlans = collect();
            $invoices = collect();
            return view('web.clinic.patients.show', compact('patient', 'appointments', 'treatmentPlans', 'invoices', 'clinic'))
                ->with('error', 'Patient not found.');
        }
        
        // Load relationships
        $appointments = $patient->appointments()->latest()->get();
        $treatmentPlans = collect([]);
        $invoices = collect([]);
        
        // Check if relations exist dynamically
        if (method_exists($patient, 'treatmentPlans')) {
            $treatmentPlans = $patient->treatmentPlans()->latest()->get();
        }

        return view('web.clinic.patients.show', compact('patient', 'appointments', 'treatmentPlans', 'invoices', 'clinic'));
    }

    /**
     * Show the form for editing the specified patient.
     */
    public function edit($id)
    {
        $clinic = $this->getUserClinic();
        
        // Try to find patient even if no clinic
        try {
            if ($clinic) {
                $patient = Patient::where('clinic_id', $clinic->id)->findOrFail($id);
            } else {
                $patient = Patient::findOrFail($id);
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('clinic.patients.index')
                ->with('error', 'Patient not found.');
        }

        return view('web.clinic.patients.edit', compact('patient', 'clinic'));
    }

    /**
     * Update the specified patient in storage.
     */
    public function update(Request $request, $id)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return back()->with('error', 'Clinic not found.');
        }

        $patient = Patient::where('clinic_id', $clinic->id)->findOrFail($id);

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
        ]);

        $patient->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'date_of_birth' => $request->dob,
            'gender' => $request->gender,
            'address' => $request->address,
            'medical_history' => $request->medical_history,
            'insurance_provider' => $request->insurance_provider,
            'insurance_number' => $request->insurance_number,
        ]);

        return redirect()->route('clinic.patients.show', $patient->id)
            ->with('success', 'Patient updated successfully.');
    }
}
