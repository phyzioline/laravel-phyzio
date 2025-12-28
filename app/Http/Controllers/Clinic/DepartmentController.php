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
        
        if (!$clinic) {
            return redirect()->route('clinic.dashboard')
                ->with('error', 'Clinic not found.');
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
            
            // Get head doctor for this specialty
            $headDoctor = User::where('type', 'therapist')
                ->where(function($q) use ($specialtyValue) {
                    $q->where('specialization', $specialtyValue)
                      ->orWhere('specialization', 'like', '%' . $specialtyValue . '%');
                })
                ->first();
            
            // Count doctors in this specialty
            $doctorsCount = User::where('type', 'therapist')
                ->where(function($q) use ($specialtyValue) {
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
        
        if (!$clinic) {
            return redirect()->route('clinic.dashboard')
                ->with('error', 'Clinic not found.');
        }

        return view('web.clinic.departments.create', compact('clinic'));
    }
}
