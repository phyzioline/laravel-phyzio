<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends BaseClinicController
{
    /**
     * Show onboarding wizard
     */
    public function index()
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return redirect()->route('clinic.dashboard')
                ->with('error', __('Clinic not found'));
        }

        // Check if onboarding is complete
        if ($this->isOnboardingComplete($clinic)) {
            return redirect()->route('clinic.dashboard')
                ->with('info', __('Onboarding already completed'));
        }

        $steps = $this->getOnboardingSteps($clinic);
        $currentStep = $this->getCurrentStep($clinic);

        return view('web.clinic.onboarding.index', compact('clinic', 'steps', 'currentStep'));
    }

    /**
     * Complete a specific onboarding step
     */
    public function completeStep(Request $request, $step)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return response()->json(['success' => false, 'message' => __('Clinic not found')], 404);
        }

        $steps = $this->getOnboardingSteps($clinic);
        
        if (!isset($steps[$step])) {
            return response()->json(['success' => false, 'message' => __('Invalid step')], 400);
        }

        // Mark step as completed
        $completedSteps = json_decode($clinic->onboarding_completed_steps ?? '[]', true);
        if (!in_array($step, $completedSteps)) {
            $completedSteps[] = $step;
            $clinic->update([
                'onboarding_completed_steps' => json_encode($completedSteps)
            ]);
        }

        // Check if all steps are complete
        if ($this->isOnboardingComplete($clinic)) {
            $clinic->update(['onboarding_completed' => true, 'onboarding_completed_at' => now()]);
        }

        return response()->json([
            'success' => true,
            'message' => __('Step completed'),
            'next_step' => $this->getNextStep($clinic),
            'is_complete' => $this->isOnboardingComplete($clinic)
        ]);
    }

    /**
     * Skip onboarding
     */
    public function skip()
    {
        $clinic = $this->getUserClinic();
        
        if ($clinic) {
            $clinic->update([
                'onboarding_completed' => true,
                'onboarding_completed_at' => now(),
                'onboarding_skipped' => true
            ]);
        }

        return redirect()->route('clinic.dashboard')
            ->with('info', __('Onboarding skipped. You can complete it later from settings.'));
    }

    /**
     * Get onboarding steps
     */
    protected function getOnboardingSteps($clinic)
    {
        return [
            'specialty' => [
                'title' => __('Select Specialty'),
                'description' => __('Choose your clinic specialty to customize the system'),
                'route' => route('clinic.specialty-selection.show'),
                'icon' => 'las la-stethoscope',
                'required' => true
            ],
            'profile' => [
                'title' => __('Complete Profile'),
                'description' => __('Add clinic information and contact details'),
                'route' => route('clinic.profile.index'),
                'icon' => 'las la-building',
                'required' => true
            ],
            'staff' => [
                'title' => __('Add Staff'),
                'description' => __('Hire your first staff member or therapist'),
                'route' => route('clinic.staff.index'),
                'icon' => 'las la-users',
                'required' => false
            ],
            'services' => [
                'title' => __('Set Up Services'),
                'description' => __('Create your clinic services and departments'),
                'route' => route('clinic.departments.index'),
                'icon' => 'las la-hospital',
                'required' => false
            ],
            'patient' => [
                'title' => __('Register First Patient'),
                'description' => __('Add your first patient to get started'),
                'route' => route('clinic.patients.create'),
                'icon' => 'las la-user-injured',
                'required' => false
            ]
        ];
    }

    /**
     * Get current step
     */
    protected function getCurrentStep($clinic)
    {
        $steps = $this->getOnboardingSteps($clinic);
        $completedSteps = json_decode($clinic->onboarding_completed_steps ?? '[]', true);

        foreach ($steps as $key => $step) {
            if (!in_array($key, $completedSteps)) {
                // Check if step is actually completed
                if ($key === 'specialty' && $clinic->hasSelectedSpecialty()) {
                    continue;
                }
                if ($key === 'profile' && $clinic->name && $clinic->phone) {
                    continue;
                }
                return $key;
            }
        }

        return null;
    }

    /**
     * Get next step
     */
    protected function getNextStep($clinic)
    {
        return $this->getCurrentStep($clinic);
    }

    /**
     * Check if onboarding is complete
     */
    protected function isOnboardingComplete($clinic)
    {
        if ($clinic->onboarding_completed) {
            return true;
        }

        // Check required steps
        if (!$clinic->hasSelectedSpecialty()) {
            return false;
        }

        if (!$clinic->name || !$clinic->phone) {
            return false;
        }

        return true;
    }
}

