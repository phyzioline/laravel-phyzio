<?php

namespace App\Http\Controllers\Clinic;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DoctorController extends BaseClinicController
{
    public function index()
    {
        $clinic = $this->getUserClinic();
        
        // Show empty state instead of redirecting
        if (!$clinic) {
            $doctors = collect();
            return view('web.clinic.doctors.index', compact('doctors', 'clinic'));
        }

        // Get real doctors/therapists linked to this clinic
        // Assuming doctors are users with type 'therapist' or 'doctor'
        $doctors = User::where('type', 'therapist')
            ->orWhere('type', 'doctor')
            ->get()
            ->map(function($doctor) use ($clinic) {
                // Get patient count for this doctor in this clinic
                $patientCount = \App\Models\ClinicAppointment::where('clinic_id', $clinic->id)
                    ->where('doctor_id', $doctor->id)
                    ->distinct('patient_id')
                    ->count('patient_id');
                
                return (object)[
                    'id' => $doctor->id,
                    'name' => $doctor->name ?? ($doctor->first_name . ' ' . $doctor->last_name),
                    'specialty' => $doctor->specialization ?? 'General',
                    'patients' => $patientCount,
                    'status' => 'Available', // TODO: Add status logic
                    'email' => $doctor->email,
                    'phone' => $doctor->phone,
                ];
            });
        
        return view('web.clinic.doctors.index', compact('doctors', 'clinic'));
    }

    public function create()
    {
        $clinic = $this->getUserClinic();
        
        // Show form even if no clinic
        return view('web.clinic.doctors.create', compact('clinic'));
    }

    public function store(Request $request)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return back()->with('error', 'Clinic not found.');
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'specialization' => 'required|string',
            'bio' => 'nullable|string',
        ]);

        $doctor = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make('password'), // Default password, should be changed
            'type' => 'therapist',
            'specialization' => $request->specialization,
            'bio' => $request->bio,
        ]);

        // Link doctor to clinic if there's a relationship table
        // This depends on your schema - might need clinic_user pivot table

        return redirect()->route('clinic.doctors.index')
            ->with('success', 'Doctor registered successfully.');
    }

    public function show($id)
    {
        $clinic = $this->getUserClinic();
        
        // Show doctor even if no clinic (might be shared doctor)
        if (!$clinic) {
            $doctor = User::where('type', 'therapist')->orWhere('type', 'doctor')->findOrFail($id);
            $doctorData = (object)[
                'id' => $doctor->id,
                'name' => $doctor->name ?? ($doctor->first_name . ' ' . $doctor->last_name),
                'specialty' => $doctor->specialization ?? 'General',
                'email' => $doctor->email,
                'phone' => $doctor->phone,
                'bio' => $doctor->bio ?? 'No bio available.',
                'patients' => 0,
                'status' => 'Available',
            ];
            return view('web.clinic.doctors.show', compact('doctor', 'doctorData', 'clinic'));
        }

        $doctor = User::where('type', 'therapist')
            ->orWhere('type', 'doctor')
            ->findOrFail($id);
        
        $patientCount = \App\Models\ClinicAppointment::where('clinic_id', $clinic->id)
            ->where('doctor_id', $doctor->id)
            ->distinct('patient_id')
            ->count('patient_id');

        $doctorData = (object)[
            'id' => $doctor->id,
            'name' => $doctor->name ?? ($doctor->first_name . ' ' . $doctor->last_name),
            'specialty' => $doctor->specialization ?? 'General',
            'email' => $doctor->email,
            'phone' => $doctor->phone,
            'bio' => $doctor->bio ?? 'No bio available.',
            'patients' => $patientCount,
            'status' => 'Available',
        ];
        
        return view('web.clinic.doctors.show', compact('doctor', 'doctorData', 'clinic'));
    }
}
