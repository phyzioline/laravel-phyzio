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
            
            // Get head doctor for this specialty (query through therapist_profiles relationship)
            $headDoctor = User::where('type', 'therapist')
                ->whereHas('therapistProfile', function($q) use ($specialtyValue) {
                    $q->where('specialization', $specialtyValue)
                      ->orWhere('specialization', 'like', '%' . $specialtyValue . '%');
                })
                ->first();
            
            // Count doctors in this specialty (query through therapist_profiles relationship)
            $doctorsCount = User::where('type', 'therapist')
                ->whereHas('therapistProfile', function($q) use ($specialtyValue) {
                    $q->where('specialization', $specialtyValue)
                      ->orWhere('specialization', 'like', '%' . $specialtyValue . '%');
                })
                ->count();
            
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
                    ->with('success', 'Department reactivated successfully.');
            } else {
                return back()->with('error', 'This department already exists.');
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
            ->with('success', 'Department added successfully.');
    }
}
