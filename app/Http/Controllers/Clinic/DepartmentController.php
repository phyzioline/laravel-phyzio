<?php

namespace App\Http\Controllers\Clinic;

use App\Models\ClinicSpecialty;
use App\Models\User;
use Illuminate\Http\Request;

class DepartmentController extends BaseClinicController
{
    public function index()
    {
        $clinic = $this->getUserClinic();
        
        // Show empty state instead of redirecting
        if (!$clinic) {
            $departments = collect();
            return view('web.clinic.departments.index', compact('departments', 'clinic'));
        }

        // Get real departments/specialties from clinic
        $specialties = ClinicSpecialty::where('clinic_id', $clinic->id)
            ->where('is_active', true)
            ->get();

        // If no specialties, show primary specialty or empty
        if ($specialties->isEmpty() && $clinic->primary_specialty) {
            $specialties = collect([
                (object)[
                    'specialty' => $clinic->primary_specialty,
                    'is_primary' => true,
                    'is_active' => true,
                ]
            ]);
        }

        // Map specialties to departments format
        $departments = $specialties->map(function($specialty) use ($clinic) {
            // Handle both object and array access
            $specialtyValue = is_object($specialty) ? $specialty->specialty : ($specialty['specialty'] ?? $clinic->primary_specialty);
            
            $displayName = ClinicSpecialty::SPECIALTIES[$specialtyValue] ?? ucfirst(str_replace('_', ' ', $specialtyValue));
            
            // Get assigned doctors for this specialty (using doctor_specialty_assignments table)
            $assignedDoctors = \App\Models\DoctorSpecialtyAssignment::where('clinic_id', $clinic->id)
                ->where('specialty', $specialtyValue)
                ->where('is_active', true)
                ->with('doctor')
                ->orderBy('is_head', 'desc')
                ->orderBy('priority', 'desc')
                ->get();
            
            // Get head doctor
            $headAssignment = $assignedDoctors->where('is_head', true)->first();
            $headDoctor = $headAssignment ? $headAssignment->doctor : null;
            
            // Count assigned doctors
            $doctorsCount = $assignedDoctors->count();
            
            // Get list of assigned doctors
            $assignedDoctorsList = $assignedDoctors->pluck('doctor')->filter();
            
            // Get description based on specialty
            $descriptions = [
                'orthopedic' => 'Musculoskeletal diagnostics and treatment.',
                'pediatric' => 'Developmental therapy for children.',
                'neurological' => 'Neurological rehabilitation and recovery.',
                'sports' => 'Injury recovery and performance enhancement.',
                'geriatric' => 'Elderly care and fall prevention.',
                'womens_health' => 'Women\'s health and pelvic floor therapy.',
                'cardiorespiratory' => 'Cardiac and respiratory rehabilitation.',
                'home_care' => 'Mobile physical therapy services.',
                'multi_specialty' => 'Comprehensive multi-specialty care.',
            ];
            
            $description = $descriptions[$specialtyValue] ?? 'Physical therapy services.';
            $isActive = is_object($specialty) ? ($specialty->is_active ?? true) : ($specialty['is_active'] ?? true);
            $isPrimary = is_object($specialty) ? ($specialty->is_primary ?? false) : ($specialty['is_primary'] ?? false);
            
            return (object)[
                'id' => is_object($specialty) ? ($specialty->id ?? null) : ($specialty['id'] ?? null),
                'name' => $displayName,
                'specialty' => $specialtyValue,
                'head' => $headDoctor ? ($headDoctor->name ?? ($headDoctor->first_name . ' ' . $headDoctor->last_name)) : 'Not Assigned',
                'doctors_count' => $doctorsCount,
                'assigned_doctors' => $assignedDoctorsList,
                'status' => $isActive ? 'Active' : 'Inactive',
                'description' => $description,
                'is_primary' => $isPrimary,
            ];
        });

        return view('web.clinic.departments.index', compact('departments', 'clinic'));
    }

    public function create()
    {
        $clinic = $this->getUserClinic();
        
        // Show form even if no clinic (user can see they need to set up clinic)
        return view('web.clinic.departments.create', compact('clinic'));
    }

    public function store(Request $request)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return back()->with('error', 'Clinic not found.');
        }

        $validator = \Validator::make($request->all(), [
            'specialty' => 'required|string|in:' . implode(',', array_keys(ClinicSpecialty::getAvailableSpecialties())),
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Check if specialty already exists for this clinic
        $existing = ClinicSpecialty::where('clinic_id', $clinic->id)
            ->where('specialty', $request->specialty)
            ->first();

        if ($existing) {
            if (!$existing->is_active) {
                // Reactivate existing specialty
                $existing->update([
                    'is_active' => true,
                    'activated_at' => now()
                ]);
                return redirect()->route('clinic.departments.index')
                    ->with('success', __('Department reactivated successfully.'));
            } else {
                return back()->with('error', __('This department already exists.'));
            }
        }

        // Create new specialty
        $isPrimary = !$clinic->hasSelectedSpecialty(); // First specialty becomes primary
        
        ClinicSpecialty::create([
            'clinic_id' => $clinic->id,
            'specialty' => $request->specialty,
            'is_primary' => $isPrimary,
            'is_active' => true,
            'activated_at' => now()
        ]);

        // If this is the first specialty, update clinic
        if ($isPrimary) {
            $clinic->update([
                'primary_specialty' => $request->specialty,
                'specialty_selected' => true,
                'specialty_selected_at' => now()
            ]);
        }

        return redirect()->route('clinic.departments.index')
            ->with('success', __('Department added successfully.'));
    }

    /**
     * Show department/service details with assigned doctors
     */
    public function show($specialty)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return redirect()->route('clinic.departments.index')
                ->with('error', __('Clinic not found.'));
        }
        
        // Get specialty info
        $clinicSpecialty = ClinicSpecialty::where('clinic_id', $clinic->id)
            ->where('specialty', $specialty)
            ->first();
        
        if (!$clinicSpecialty && $clinic->primary_specialty === $specialty) {
            // Use primary specialty if not in clinic_specialties table
            $clinicSpecialty = (object)[
                'specialty' => $specialty,
                'is_primary' => true,
                'is_active' => true,
            ];
        }
        
        if (!$clinicSpecialty) {
            return redirect()->route('clinic.departments.index')
                ->with('error', __('Department not found.'));
        }
        
        $displayName = ClinicSpecialty::SPECIALTIES[$specialty] ?? ucfirst(str_replace('_', ' ', $specialty));
        
        // Get assigned doctors
        $assignedDoctors = \App\Models\DoctorSpecialtyAssignment::where('clinic_id', $clinic->id)
            ->where('specialty', $specialty)
            ->where('is_active', true)
            ->with('doctor')
            ->orderBy('is_head', 'desc')
            ->orderBy('priority', 'desc')
            ->get();
        
        // Get all available doctors (not yet assigned to this specialty)
        $assignedDoctorIds = $assignedDoctors->pluck('doctor_id')->toArray();
        $availableDoctors = \App\Models\User::whereHas('clinicStaff', function($q) use ($clinic) {
            $q->where('clinic_id', $clinic->id)
              ->whereIn('role', ['therapist', 'doctor'])
              ->where('is_active', true);
        })
        ->whereIn('type', ['therapist', 'doctor'])
        ->whereNotIn('id', $assignedDoctorIds)
        ->get();
        
        // Get statistics
        $totalAppointments = \App\Models\ClinicAppointment::where('clinic_id', $clinic->id)
            ->where('specialty', $specialty)
            ->count();
        
        $totalPatients = \App\Models\ClinicAppointment::where('clinic_id', $clinic->id)
            ->where('specialty', $specialty)
            ->distinct('patient_id')
            ->count('patient_id');
        
        return view('web.clinic.departments.show', compact(
            'clinic',
            'specialty',
            'displayName',
            'clinicSpecialty',
            'assignedDoctors',
            'availableDoctors',
            'totalAppointments',
            'totalPatients'
        ));
    }

    /**
     * Assign doctor to specialty
     */
    public function assignDoctor(Request $request, $specialty)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return response()->json([
                'success' => false,
                'message' => 'Clinic not found.'
            ], 404);
        }
        
        $request->validate([
            'doctor_id' => 'required|exists:users,id',
            'is_head' => 'nullable|boolean',
            'priority' => 'nullable|integer|min:0|max:100'
        ]);
        
        // Verify doctor belongs to clinic
        $clinicStaff = \App\Models\ClinicStaff::where('clinic_id', $clinic->id)
            ->where('user_id', $request->doctor_id)
            ->whereIn('role', ['therapist', 'doctor'])
            ->where('is_active', true)
            ->first();
        
        if (!$clinicStaff) {
            return response()->json([
                'success' => false,
                'message' => __('Doctor is not assigned to this clinic.')
            ], 422);
        }
        
        // Check if already assigned
        $existing = \App\Models\DoctorSpecialtyAssignment::where('clinic_id', $clinic->id)
            ->where('doctor_id', $request->doctor_id)
            ->where('specialty', $specialty)
            ->first();
        
        if ($existing) {
            // Update existing assignment
            $existing->update([
                'is_head' => $request->is_head ?? false,
                'priority' => $request->priority ?? 0,
                'is_active' => true
            ]);
        } else {
            // Create new assignment
            \App\Models\DoctorSpecialtyAssignment::create([
                'clinic_id' => $clinic->id,
                'doctor_id' => $request->doctor_id,
                'specialty' => $specialty,
                'is_head' => $request->is_head ?? false,
                'priority' => $request->priority ?? 0,
                'is_active' => true
            ]);
        }
        
        // If setting as head, remove head status from others
        if ($request->is_head) {
            \App\Models\DoctorSpecialtyAssignment::where('clinic_id', $clinic->id)
                ->where('specialty', $specialty)
                ->where('doctor_id', '!=', $request->doctor_id)
                ->update(['is_head' => false]);
        }
        
        return response()->json([
            'success' => true,
            'message' => __('Doctor assigned successfully.')
        ]);
    }

    /**
     * Unassign doctor from specialty
     */
    public function unassignDoctor($specialty, $doctorId)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return response()->json([
                'success' => false,
                'message' => 'Clinic not found.'
            ], 404);
        }
        
        $assignment = \App\Models\DoctorSpecialtyAssignment::where('clinic_id', $clinic->id)
            ->where('specialty', $specialty)
            ->where('doctor_id', $doctorId)
            ->first();
        
        if ($assignment) {
            $assignment->update(['is_active' => false]);
            // Or delete: $assignment->delete();
        }
        
        return response()->json([
            'success' => true,
            'message' => __('Doctor unassigned successfully.')
        ]);
    }
}
