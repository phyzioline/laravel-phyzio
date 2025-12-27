<?php

namespace App\Services\Clinic;

use App\Models\Clinic;
use App\Models\ClinicSpecialty;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SpecialtySelectionService
{
    /**
     * Select specialty for a clinic
     * 
     * @param Clinic $clinic
     * @param string|array $specialties
     * @param string|null $primarySpecialty
     * @return bool
     */
    public function selectSpecialty(Clinic $clinic, $specialties, ?string $primarySpecialty = null): bool
    {
        try {
            DB::beginTransaction();

            // Convert single specialty to array
            if (is_string($specialties)) {
                $specialties = [$specialties];
            }

            // Validate specialties
            $availableSpecialties = ClinicSpecialty::getAvailableSpecialties();
            foreach ($specialties as $specialty) {
                if (!isset($availableSpecialties[$specialty])) {
                    throw new \InvalidArgumentException("Invalid specialty: {$specialty}");
                }
            }

            // If primary specialty is not provided, use the first one
            if (!$primarySpecialty && !empty($specialties)) {
                $primarySpecialty = $specialties[0];
            }

            // Validate primary specialty is in the list
            if ($primarySpecialty && !in_array($primarySpecialty, $specialties)) {
                throw new \InvalidArgumentException("Primary specialty must be one of the selected specialties");
            }

            // Update clinic primary specialty
            $clinic->update([
                'primary_specialty' => $primarySpecialty,
                'specialty_selected' => true,
                'specialty_selected_at' => now()
            ]);

            // Remove existing specialties for this clinic
            ClinicSpecialty::where('clinic_id', $clinic->id)->delete();

            // Create new specialty records
            foreach ($specialties as $specialty) {
                ClinicSpecialty::create([
                    'clinic_id' => $clinic->id,
                    'specialty' => $specialty,
                    'is_primary' => ($specialty === $primarySpecialty),
                    'is_active' => true,
                    'activated_at' => now()
                ]);
            }

            DB::commit();

            Log::info("Specialty selected for clinic", [
                'clinic_id' => $clinic->id,
                'specialties' => $specialties,
                'primary' => $primarySpecialty
            ]);

            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Failed to select specialty for clinic", [
                'clinic_id' => $clinic->id,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Add additional specialty to existing clinic
     * 
     * @param Clinic $clinic
     * @param string $specialty
     * @return bool
     */
    public function addSpecialty(Clinic $clinic, string $specialty): bool
    {
        // Validate specialty
        $availableSpecialties = ClinicSpecialty::getAvailableSpecialties();
        if (!isset($availableSpecialties[$specialty])) {
            throw new \InvalidArgumentException("Invalid specialty: {$specialty}");
        }

        // Check if already exists
        if ($clinic->hasSpecialty($specialty)) {
            return true; // Already exists
        }

        // Create new specialty record
        ClinicSpecialty::create([
            'clinic_id' => $clinic->id,
            'specialty' => $specialty,
            'is_primary' => false,
            'is_active' => true,
            'activated_at' => now()
        ]);

        return true;
    }

    /**
     * Remove specialty from clinic
     * 
     * @param Clinic $clinic
     * @param string $specialty
     * @return bool
     */
    public function removeSpecialty(Clinic $clinic, string $specialty): bool
    {
        // Don't allow removing primary specialty if it's the only one
        $activeSpecialties = $clinic->activeSpecialties;
        if ($activeSpecialties->count() <= 1) {
            throw new \InvalidArgumentException("Cannot remove the last specialty. Please select a new primary specialty first.");
        }

        // If removing primary, set first available as primary
        $primarySpecialty = $clinic->primarySpecialty;
        if ($primarySpecialty && $primarySpecialty->specialty === $specialty) {
            $newPrimary = $activeSpecialties->where('specialty', '!=', $specialty)->first();
            if ($newPrimary) {
                $clinic->update(['primary_specialty' => $newPrimary->specialty]);
                $newPrimary->update(['is_primary' => true]);
            }
        }

        // Deactivate specialty
        ClinicSpecialty::where('clinic_id', $clinic->id)
            ->where('specialty', $specialty)
            ->update(['is_active' => false]);

        return true;
    }

    /**
     * Get clinic's active specialties
     * 
     * @param Clinic $clinic
     * @return \Illuminate\Support\Collection
     */
    public function getActiveSpecialties(Clinic $clinic)
    {
        return $clinic->activeSpecialties;
    }

    /**
     * Check if clinic needs specialty selection
     * 
     * @param Clinic $clinic
     * @return bool
     */
    public function needsSpecialtySelection(Clinic $clinic): bool
    {
        return !$clinic->hasSelectedSpecialty();
    }
}

