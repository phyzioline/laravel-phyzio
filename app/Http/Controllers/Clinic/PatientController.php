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
            return redirect()->back()
                ->withInput()
                ->with('error', 'Clinic not found. Please contact support.');
        }

        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'email' => 'nullable|email|max:255',
                'dob' => 'nullable|date',
                'gender' => 'nullable|in:male,female',
                'address' => 'nullable|string|max:500',
                'medical_history' => 'nullable|string',
                'insurance_provider' => 'nullable|string|max:255',
                'insurance_number' => 'nullable|string|max:255',
            ]);

            $patient = Patient::create([
                'clinic_id' => $clinic->id,
                'first_name' => $validated['first_name'],
                'last_name' => $validated['last_name'],
                'phone' => $validated['phone'],
                'email' => $validated['email'] ?? null,
                'date_of_birth' => $validated['dob'] ?? null,
                'gender' => $validated['gender'] ?? null,
                'address' => $validated['address'] ?? null,
                'medical_history' => $validated['medical_history'] ?? null,
                'insurance_provider' => $validated['insurance_provider'] ?? null,
                'insurance_number' => $validated['insurance_number'] ?? null,
            ]);

            return redirect()->route('clinic.patients.show', $patient->id)
                ->with('success', 'Patient registered successfully.');
                
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Exception $e) {
            \Log::error('Patient registration error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to register patient. Please try again.');
        }
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
        $appointments = $patient->appointments()->with('doctor')->latest()->get();
        $treatmentPlans = collect([]);
        $invoices = collect([]);
        $attachments = collect([]);
        $payments = collect([]);
        $sessionsTimeline = collect([]);
        
        // Check if relations exist dynamically
        if (method_exists($patient, 'treatmentPlans')) {
            $treatmentPlans = $patient->treatmentPlans()->latest()->get();
        }
        
        // Load invoices for this patient
        if ($clinic && class_exists(\App\Models\PatientInvoice::class)) {
            $invoices = \App\Models\PatientInvoice::where('patient_id', $patient->id)
                ->where('clinic_id', $clinic->id)
                ->with('payments')
                ->latest()
                ->get();
        }
        
        // Load payments
        if ($clinic && class_exists(\App\Models\PatientPayment::class)) {
            $payments = \App\Models\PatientPayment::where('patient_id', $patient->id)
                ->where('clinic_id', $clinic->id)
                ->with('invoice')
                ->latest()
                ->get();
        }
        
        // Load attachments
        if ($clinic && \Schema::hasTable('patient_attachments')) {
            $attachments = \App\Models\PatientAttachment::where('patient_id', $patient->id)
                ->where('clinic_id', $clinic->id)
                ->with('uploadedBy')
                ->latest()
                ->get();
        }
        
        // Build session timeline (combine appointments and program sessions chronologically)
        $sessionsTimeline = collect();
        
        // Add appointments to timeline
        foreach ($appointments as $appt) {
            $sessionsTimeline->push((object)[
                'type' => 'appointment',
                'date' => $appt->appointment_date,
                'title' => 'Appointment - ' . ($appt->specialty ?? 'General'),
                'status' => $appt->status,
                'data' => $appt
            ]);
        }
        
        // Add program sessions if available
        if (\Schema::hasTable('program_sessions') && method_exists($patient, 'programSessions')) {
            try {
                $programSessions = \App\Models\ProgramSession::whereHas('program', function($q) use ($patient) {
                    $q->where('patient_id', $patient->id);
                })
                ->with('program')
                ->orderBy('scheduled_date', 'desc')
                ->get();
                
                foreach ($programSessions as $session) {
                    $sessionsTimeline->push((object)[
                        'type' => 'session',
                        'date' => $session->scheduled_date,
                        'title' => 'Session #' . $session->session_in_program . ' - ' . ucfirst($session->session_type),
                        'status' => $session->status,
                        'data' => $session
                    ]);
                }
            } catch (\Exception $e) {
                // Ignore if tables don't exist
            }
        }
        
        // Sort timeline by date (newest first)
        $sessionsTimeline = $sessionsTimeline->sortByDesc(function($item) {
            return $item->date instanceof \Carbon\Carbon ? $item->date : \Carbon\Carbon::parse($item->date);
        })->values();

        return view('web.clinic.patients.show', compact(
            'patient', 
            'appointments', 
            'treatmentPlans', 
            'invoices', 
            'clinic',
            'attachments',
            'payments',
            'sessionsTimeline'
        ));
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

    /**
     * Store patient attachment
     */
    public function storeAttachment(Request $request, $id)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return redirect()->back()->with('error', 'Clinic not found.');
        }

        $patient = Patient::where('clinic_id', $clinic->id)->findOrFail($id);

        $validated = $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:10240', // 10MB max
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'category' => 'nullable|in:xray,mri,lab_report,doctor_note,prescription,insurance,other',
            'document_date' => 'nullable|date'
        ]);

        try {
            $file = $request->file('file');
            $path = $file->store('patient-attachments', 'public');
            
            $attachment = \App\Models\PatientAttachment::create([
                'patient_id' => $patient->id,
                'clinic_id' => $clinic->id,
                'uploaded_by' => Auth::id(),
                'file_name' => $file->getClientOriginalName(),
                'file_path' => $path,
                'file_type' => $file->getClientOriginalExtension(),
                'mime_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'title' => $validated['title'] ?? $file->getClientOriginalName(),
                'description' => $validated['description'] ?? null,
                'category' => $validated['category'] ?? 'other',
                'document_date' => $validated['document_date'] ?? null
            ]);

            return redirect()->route('clinic.patients.show', $patient->id)
                ->with('success', 'Attachment uploaded successfully.');
        } catch (\Exception $e) {
            \Log::error('Attachment upload error: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Failed to upload attachment. Please try again.');
        }
    }
}
