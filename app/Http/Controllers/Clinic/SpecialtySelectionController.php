<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\ClinicSpecialty;
use App\Services\Clinic\SpecialtySelectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SpecialtySelectionController extends BaseClinicController
{
    protected $specialtySelectionService;

    public function __construct(SpecialtySelectionService $specialtySelectionService)
    {
        $this->specialtySelectionService = $specialtySelectionService;
    }

    /**
     * Show specialty selection form
     */
    public function show()
    {
        $user = Auth::user();
        
        // Get clinic - assuming user has a clinic relationship
        // Adjust this based on your actual user-clinic relationship
        $clinic = $this->getUserClinic($user);
        
        // Show empty state instead of redirecting
        if (!$clinic) {
            $availableSpecialties = ClinicSpecialty::getAvailableSpecialties();
            return view('web.clinic.specialty-selection', compact('clinic', 'availableSpecialties'))
                ->with('error', 'Clinic not found. Please contact support to set up your clinic.');
        }

        // Check if already selected
        if ($clinic->hasSelectedSpecialty()) {
            return redirect()->route('clinic.dashboard')
                ->with('info', 'Specialty already selected. You can manage specialties from settings.');
        }

        $availableSpecialties = ClinicSpecialty::getAvailableSpecialties();

        return view('web.clinic.specialty-selection', compact('clinic', 'availableSpecialties'));
    }

    /**
     * Store specialty selection
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $clinic = $this->getUserClinic($user);

        if (!$clinic) {
            return response()->json([
                'success' => false,
                'message' => 'Clinic not found.'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'specialties' => 'required|array|min:1',
            'specialties.*' => 'required|string|in:' . implode(',', array_keys(ClinicSpecialty::getAvailableSpecialties())),
            'primary_specialty' => 'nullable|string|in:' . implode(',', array_keys(ClinicSpecialty::getAvailableSpecialties()))
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $specialties = $request->input('specialties');
            $primarySpecialty = $request->input('primary_specialty', $specialties[0] ?? null);

            $this->specialtySelectionService->selectSpecialty(
                $clinic,
                $specialties,
                $primarySpecialty
            );

            return response()->json([
                'success' => true,
                'message' => 'Specialty selected successfully.',
                'redirect' => route('clinic.dashboard')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to select specialty: ' . $e->getMessage()
            ], 500);
        }
    }

    // getUserClinic method inherited from BaseClinicController
}

