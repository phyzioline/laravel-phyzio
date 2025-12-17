<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    /**
     * Display a listing of the patients.
     */
    public function index(Request $request)
    {
        $query = Patient::query(); // Add ->where('clinic_id', Auth::user()->clinic_id) if multi-tenant

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

        return view('web.clinic.patients.index', compact('patients'));
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
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
        ]);

        $patient = new Patient();
        // $patient->clinic_id = Auth::user()->clinic_id; // Uncomment if applicable
        $patient->first_name = $request->first_name;
        $patient->last_name = $request->last_name;
        $patient->phone = $request->phone;
        $patient->email = $request->email;
        $patient->date_of_birth = $request->dob; // Mapped to correct column
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
        $patient = Patient::findOrFail($id);
        // Load relationships (appointments, plans, invoices)
        // $patient->load(['appointments', 'treatmentPlans', 'invoices']); 
        
        // Mocking Data for View Development until relations are fully set in Models
        $appointments = collect([]); 
        $treatmentPlans = collect([]);
        $invoices = collect([]);
        
        // Check if relations exist dynamically to avoid crashing
        if (method_exists($patient, 'appointments')) $appointments = $patient->appointments()->latest()->get();
        if (method_exists($patient, 'treatmentPlans')) $treatmentPlans = $patient->treatmentPlans()->latest()->get();
        // if (method_exists($patient, 'invoices')) $invoices = $patient->invoices()->latest()->get();

        return view('web.clinic.patients.show', compact('patient', 'appointments', 'treatmentPlans', 'invoices'));
    }

    /**
     * Show the form for editing the specified patient.
     */
    public function edit($id)
    {
        $patient = Patient::findOrFail($id);
        return view('web.clinic.patients.edit', compact('patient'));
    }

    /**
     * Update the specified patient in storage.
     */
    public function update(Request $request, $id)
    {
        // Add update logic
        return back();
    }
}
