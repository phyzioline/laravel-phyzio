<?php

namespace App\Services\Billing;

use App\Models\InsuranceClaim;
use App\Models\ClaimDenial;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class DenialManagementService
{
    /**
     * Process claim denial
     *
     * @param InsuranceClaim $claim
     * @param string $denialCode
     * @param string $denialReason
     * @param string|null $description
     * @return ClaimDenial
     */
    public function processDenial(
        InsuranceClaim $claim,
        string $denialCode,
        string $denialReason,
        ?string $description = null
    ): ClaimDenial {
        // Determine severity based on denial code
        $severity = $this->determineSeverity($denialCode);

        $denial = ClaimDenial::create([
            'claim_id' => $claim->id,
            'denial_code' => $denialCode,
            'denial_reason' => $denialReason,
            'description' => $description,
            'severity' => $severity,
            'status' => 'new',
        ]);

        // Update claim status
        $claim->update([
            'status' => 'denied',
            'denial_reason' => $denialReason,
            'denial_code' => $denialCode,
            'processed_at' => now(),
        ]);

        Log::info('Claim denial processed', [
            'claim_id' => $claim->id,
            'denial_code' => $denialCode,
        ]);

        return $denial;
    }

    /**
     * Process ERA (Electronic Remittance Advice) and extract denials
     *
     * @param InsuranceClaim $claim
     * @param array $eraData
     * @return array
     */
    public function processERA(InsuranceClaim $claim, array $eraData): array
    {
        $denials = [];

        // Extract payment information
        $claim->update([
            'allowed_amount' => $eraData['allowed_amount'] ?? null,
            'paid_amount' => $eraData['paid_amount'] ?? null,
            'patient_responsibility' => $eraData['patient_responsibility'] ?? null,
            'processed_at' => now(),
            'era_data' => $eraData,
        ]);

        // Process adjustments/denials from ERA
        if (isset($eraData['adjustments']) && is_array($eraData['adjustments'])) {
            foreach ($eraData['adjustments'] as $adjustment) {
                $adjustmentCode = $adjustment['code'] ?? null;
                $adjustmentReason = $adjustment['reason'] ?? null;
                $amount = $adjustment['amount'] ?? 0;

                // If adjustment indicates denial
                if ($this->isDenialCode($adjustmentCode)) {
                    $denial = $this->processDenial(
                        $claim,
                        $adjustmentCode,
                        $adjustmentReason ?? 'Claim adjustment',
                        "ERA adjustment: {$adjustmentReason}"
                    );
                    $denials[] = $denial;
                }
            }
        }

        // Update claim status based on payment
        if ($claim->paid_amount > 0) {
            if ($claim->paid_amount >= $claim->billed_amount) {
                $claim->update(['status' => 'paid']);
            } else {
                $claim->update(['status' => 'partial']);
            }
        }

        return $denials;
    }

    /**
     * Appeal a denial
     *
     * @param ClaimDenial $denial
     * @param string $appealReason
     * @return bool
     */
    public function appealDenial(ClaimDenial $denial, string $appealReason): bool
    {
        try {
            $denial->update([
                'status' => 'appealed',
                'resolution_notes' => $appealReason,
            ]);

            // Update claim status
            $denial->claim->update([
                'status' => 'pending_review',
                'requires_resubmission' => true,
            ]);

            Log::info('Denial appealed', [
                'denial_id' => $denial->id,
                'claim_id' => $denial->claim_id,
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to appeal denial', [
                'denial_id' => $denial->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Resolve denial
     *
     * @param ClaimDenial $denial
     * @param string $resolutionNotes
     * @param int|null $resolvedBy
     * @return bool
     */
    public function resolveDenial(ClaimDenial $denial, string $resolutionNotes, ?int $resolvedBy = null): bool
    {
        try {
            $denial->update([
                'status' => 'resolved',
                'resolution_notes' => $resolutionNotes,
                'resolved_at' => now(),
                'resolved_by' => $resolvedBy ?? auth()->id(),
            ]);

            Log::info('Denial resolved', [
                'denial_id' => $denial->id,
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to resolve denial', [
                'denial_id' => $denial->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Get denial statistics
     *
     * @param int $clinicId
     * @param Carbon|null $startDate
     * @param Carbon|null $endDate
     * @return array
     */
    public function getDenialStats(int $clinicId, ?\Carbon\Carbon $startDate = null, ?\Carbon\Carbon $endDate = null): array
    {
        $startDate = $startDate ?? now()->subMonth();
        $endDate = $endDate ?? now();

        $denials = ClaimDenial::whereHas('claim', function ($query) use ($clinicId, $startDate, $endDate) {
            $query->where('clinic_id', $clinicId)
                ->whereBetween('created_at', [$startDate, $endDate]);
        })->get();

        return [
            'total' => $denials->count(),
            'by_severity' => $denials->groupBy('severity')->map->count(),
            'by_status' => $denials->groupBy('status')->map->count(),
            'by_code' => $denials->groupBy('denial_code')->map->count(),
            'top_denial_codes' => $denials->groupBy('denial_code')
                ->map->count()
                ->sortDesc()
                ->take(10)
                ->toArray(),
        ];
    }

    /**
     * Determine severity based on denial code
     *
     * @param string $denialCode
     * @return string
     */
    protected function determineSeverity(string $denialCode): string
    {
        // Common denial codes and their severity
        $criticalCodes = ['CO-4', 'CO-11', 'CO-16', 'CO-18']; // Coverage, authorization issues
        $highCodes = ['CO-97', 'CO-151', 'PR-1', 'PR-2']; // Benefit, payment issues
        $mediumCodes = ['CO-45', 'CO-50', 'PR-96']; // Charge, duplicate issues

        if (in_array($denialCode, $criticalCodes)) {
            return 'critical';
        } elseif (in_array($denialCode, $highCodes)) {
            return 'high';
        } elseif (in_array($denialCode, $mediumCodes)) {
            return 'medium';
        }

        return 'low';
    }

    /**
     * Check if code indicates a denial
     *
     * @param string|null $code
     * @return bool
     */
    protected function isDenialCode(?string $code): bool
    {
        if (!$code) {
            return false;
        }

        // CARC codes that indicate denials
        $denialCodes = ['CO-4', 'CO-11', 'CO-16', 'CO-18', 'CO-45', 'CO-50', 'CO-97', 'CO-151', 'PR-1', 'PR-2', 'PR-96'];

        return in_array($code, $denialCodes);
    }
}

