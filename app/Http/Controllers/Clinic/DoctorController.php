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
        // Get doctor IDs who have appointments in this clinic
        $doctorIds = \App\Models\ClinicAppointment::where('clinic_id', $clinic->id)
            ->whereNotNull('doctor_id')
            ->distinct()
            ->pluck('doctor_id')
            ->toArray();
        
        // Also check if doctors are linked via company_id (if column exists)
        $query = User::whereIn('type', ['therapist', 'doctor']);
        
        if (!empty($doctorIds)) {
            $query->whereIn('id', $doctorIds);
        } elseif (\Schema::hasColumn('users', 'company_id')) {
            // Fallback: show doctors from same company
            $query->where('company_id', $clinic->company_id);
        } else {
            // If no linking mechanism, show all therapists (for now)
            $query->where('type', 'therapist');
        }
        
        $doctors = $query->get()
            ->map(function($doctor) use ($clinic) {
                // Get patient count for this doctor in this clinic
                $patientCount = \App\Models\ClinicAppointment::where('clinic_id', $clinic->id)
                    ->where('doctor_id', $doctor->id)
                    ->distinct('patient_id')
                    ->count('patient_id');
                
                // Get appointment count for this doctor
                $appointmentCount = \App\Models\ClinicAppointment::where('clinic_id', $clinic->id)
                    ->where('doctor_id', $doctor->id)
                    ->count();
                
                // Get today's appointments
                $todayAppointments = \App\Models\ClinicAppointment::where('clinic_id', $clinic->id)
                    ->where('doctor_id', $doctor->id)
                    ->whereDate('appointment_date', today())
                    ->where('status', '!=', 'cancelled')
                    ->count();
                
                // Determine status based on appointments
                $status = 'Available';
                if ($todayAppointments > 0) {
                    $status = 'Busy';
                }
                
                // Check if doctor has upcoming appointments
                $upcomingCount = \App\Models\ClinicAppointment::where('clinic_id', $clinic->id)
                    ->where('doctor_id', $doctor->id)
                    ->where('appointment_date', '>', now())
                    ->where('status', '!=', 'cancelled')
                    ->count();
                
                if ($upcomingCount > 0 && $todayAppointments == 0) {
                    $status = 'Scheduled';
                }
                
                return (object)[
                    'id' => $doctor->id,
                    'name' => $doctor->name ?? ($doctor->first_name . ' ' . $doctor->last_name),
                    'specialty' => $doctor->specialization ?? 'General',
                    'patients' => $patientCount,
                    'appointments' => $appointmentCount,
                    'status' => $status,
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

        $validator = \Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'specialization' => 'required|string',
            'bio' => 'nullable|string',
            'password' => 'nullable|string|min:8',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $doctorData = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password ?? 'password'), // Default password, should be changed
            'type' => 'therapist',
            'specialization' => $request->specialization,
            'bio' => $request->bio,
        ];
        
        // Link doctor to clinic's company if company_id column exists
        if (\Schema::hasColumn('users', 'company_id')) {
            $doctorData['company_id'] = $clinic->company_id;
        }

        $doctor = User::create($doctorData);

        return redirect()->route('clinic.doctors.index')
            ->with('success', 'Doctor registered successfully. They can now be assigned to appointments.');
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

        // Get appointment count
        $appointmentCount = \App\Models\ClinicAppointment::where('clinic_id', $clinic->id)
            ->where('doctor_id', $doctor->id)
            ->count();
        
        // Get today's appointments
        $todayAppointments = \App\Models\ClinicAppointment::where('clinic_id', $clinic->id)
            ->where('doctor_id', $doctor->id)
            ->whereDate('appointment_date', today())
            ->where('status', '!=', 'cancelled')
            ->count();
        
        // Determine status
        $status = 'Available';
        if ($todayAppointments > 0) {
            $status = 'Busy';
        } else {
            $upcomingCount = \App\Models\ClinicAppointment::where('clinic_id', $clinic->id)
                ->where('doctor_id', $doctor->id)
                ->where('appointment_date', '>', now())
                ->where('status', '!=', 'cancelled')
                ->count();
            
            if ($upcomingCount > 0) {
                $status = 'Scheduled';
            }
        }
        
        $doctorData = (object)[
            'id' => $doctor->id,
            'name' => $doctor->name ?? ($doctor->first_name . ' ' . $doctor->last_name),
            'specialty' => $doctor->specialization ?? 'General',
            'email' => $doctor->email,
            'phone' => $doctor->phone,
            'bio' => $doctor->bio ?? 'No bio available.',
            'patients' => $patientCount,
            'appointments' => $appointmentCount,
            'status' => $status,
        ];
        
        return view('web.clinic.doctors.show', compact('doctor', 'doctorData', 'clinic'));
    }
}
