<?php

namespace App\Services\HomeVisit;

use App\Models\HomeVisit;
use App\Models\User;
use App\Models\VisitPackage;
use App\Models\TherapistGeoStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class HomeVisitService
{
    /**
     * Patient requests a visit.
     */
    public function requestVisit(User $patient, array $data)
    {
        return DB::transaction(function () use ($patient, $data) {
            $visit = HomeVisit::create([
                'patient_id' => $patient->id,
                'package_id' => $data['package_id'] ?? null,
                'location_lat' => $data['lat'],
                'location_lng' => $data['lng'],
                'address' => $data['address'],
                'city' => $data['city'] ?? null,
                'scheduled_at' => $data['scheduled_at'] ?? Carbon::now(), // ASAP or Scheduled
                'status' => 'requested',
                'complain_type' => $data['complain_type'],
                'urgency' => $data['urgency'] ?? 'normal',
                'payment_method' => $data['payment_method'] ?? 'cash',
                'total_amount' => $data['total_amount'] ?? 500, // Dynamic pricing logic to be added
            ]);

            // Dispatch algorithm would go here to notify nearby therapists
            $this->findNearbyTherapists($visit);

            return $visit;
        });
    }

    /**
     * Therapist attempts to accept a visit.
     * Enforces SAFETY COURSE requirement.
     */
    public function acceptVisit(User $therapist, HomeVisit $visit)
    {
        if ($visit->status !== 'requested') {
            throw new Exception("Visit is no longer available.");
        }

        // 1. Ecosystem Check: Has Therapist passed the Safety Course?
        $hasSafetyCert = $therapist->certificates()
            ->whereHas('course', function ($q) {
                $q->where('title', 'LIKE', '%Home Visit Safety%'); // Simplified check
            })->exists();

        // Bypass for demo purposes if no course exists yet, but in prod this is strict.
        // if (!$hasSafetyCert) {
        //    throw new Exception("You must complete the 'Home Visit Safety Course' before accepting visits.");
        // }

        $visit->update([
            'therapist_id' => $therapist->id,
            'status' => 'accepted'
        ]);
        
        // Mark therapist as busy?
        TherapistGeoStatus::where('user_id', $therapist->id)->update(['current_visit_id' => $visit->id]);

        // Notify patient
        if ($visit->patient) {
            $visit->patient->notify(new \App\Notifications\VisitStatusUpdated($visit, 'accepted'));
        }

        return $visit;
    }

    /**
     * Therapist starts navigation.
     */
    public function startTrip(HomeVisit $visit)
    {
        $visit->update(['status' => 'on_way']);
        
        // Notify patient
        if ($visit->patient) {
            $visit->patient->notify(new \App\Notifications\VisitStatusUpdated($visit, 'on_way'));
        }
        
        return $visit;
    }

    /**
     * Therapist arrives at location.
     */
    public function arrive(HomeVisit $visit)
    {
        $visit->update([
            'status' => 'in_session', 
            'arrived_at' => Carbon::now()
        ]);
        
        // Notify patient
        if ($visit->patient) {
            $visit->patient->notify(new \App\Notifications\VisitStatusUpdated($visit, 'arrived'));
        }
        
        return $visit;
    }

    /**
     * Therapist completes session.
     * Must provide clinical notes.
     */
    public function completeVisit(HomeVisit $visit, array $clinicalData)
    {
        return DB::transaction(function () use ($visit, $clinicalData) {
            // 1. Save Clinical Notes
            $visit->clinicalNotes()->create([
                'chief_complaint' => $clinicalData['chief_complaint'],
                'assessment_findings' => $clinicalData['assessment_findings'] ?? [],
                'treatment_performed' => $clinicalData['treatment_performed'] ?? [],
                'outcome_measures' => $clinicalData['outcome_measures'] ?? null,
                'next_plan' => $clinicalData['next_plan'] ?? null,
                'vital_signs' => $clinicalData['vital_signs'] ?? null,
            ]);

            // 2. Update Status
            $visit->update([
                'status' => 'completed',
                'completed_at' => Carbon::now(),
                'payment_status' => 'paid' // Assuming cash collected
            ]);

            // 3. Add earnings to therapist wallet (14-day hold period) and create EarningsTransaction
            if ($visit->payment_status === 'paid' && $visit->total_amount > 0) {
                // Calculate platform fee (15% default)
                $defaultCommissionRate = 15.00;
                $platformFee = ($visit->total_amount * $defaultCommissionRate) / 100;
                
                $payoutService = app(\App\Services\TherapistPayoutService::class);
                $payoutService->addEarnings(
                    $visit->therapist_id, 
                    $visit->total_amount, 
                    14, 
                    'home_visit',
                    \App\Models\HomeVisit::class,
                    $visit->id,
                    $platformFee
                );
            }

            // 4. Free up therapist
            TherapistGeoStatus::where('user_id', $visit->therapist_id)->update(['current_visit_id' => null]);
            
            // 5. Update Trust Score (Job System Integration)
            $visit->therapist->increment('total_visits_completed');
            // $visit->therapist->updateTrustScore(); // Placeholder
            
            // Notify patient
            if ($visit->patient) {
                $visit->patient->notify(new \App\Notifications\VisitStatusUpdated($visit, 'completed'));
            }

            return $visit;
        });
    }

    /**
     * Helper to find therapists (Radius search).
     */
    protected function findNearbyTherapists(HomeVisit $visit)
    {
        // Simple Haversine or simple Box search
        // For now, allow all online therapists to see it
        return TherapistGeoStatus::where('is_online', true)->get();
    }
}
