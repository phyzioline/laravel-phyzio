<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientController extends Controller
{
    public function index()
    {
        $clinic = Clinic::where('company_id', Auth::id())->firstOrFail();
        $patients = Patient::where('clinic_id', $clinic->id)->latest()->paginate(15);
        
        return view('web.clinic.patients.index', compact('patients', 'clinic'));
    }

    public function create()
    {
        return view('web.clinic.patients.create');
    }

    public function store(Request $request)
    {
        $clinic = Clinic::where('company_id', Auth::id())->firstOrFail();
        
        $validated = $request->validate([
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone' => 'required|string',
            'email' => 'nullable|email',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
            'address' => 'nullable|string',
            'medical_history' => 'nullable|string'
        ]);

        $validated['clinic_id'] = $clinic->id;
        Patient::create($validated);

        return redirect()->route('clinic.patients.index')->with('success', 'Patient added successfully!');
    }
}
