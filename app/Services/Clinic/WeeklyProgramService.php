<?php

namespace App\Services\Clinic;

use App\Models\WeeklyProgram;
use App\Models\ProgramSession;
use App\Models\ClinicAppointment;
use App\Services\Clinic\PaymentCalculatorService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WeeklyProgramService
{
    protected $paymentCalculator;

    public function __construct(PaymentCalculatorService $paymentCalculator)
    {
        $this->paymentCalculator = $paymentCalculator;
    }

    /**
     * Create a weekly program
     * 
     * @param array $data
     * @return WeeklyProgram
     */
    public function createProgram(array $data): WeeklyProgram
    {
        try {
            DB::beginTransaction();

            // Ensure numeric values (cast strings to integers)
            $sessionsPerWeek = (int) $data['sessions_per_week'];
            $totalWeeks = (int) $data['total_weeks'];

            // Calculate total sessions
            $totalSessions = $sessionsPerWeek * $totalWeeks;

            // Calculate pricing
            $pricingData = $this->paymentCalculator->calculateProgramPrice(
                $data['clinic'],
                [
                    'specialty' => $data['specialty'],
                    'sessions_per_week' => $sessionsPerWeek,
                    'total_weeks' => $totalWeeks,
                    'location' => $data['location'] ?? 'clinic',
                    'duration_minutes' => $data['duration_minutes'] ?? 60,
                    'therapist_level' => $data['therapist_level'] ?? 'senior'
                ]
            );

            // Calculate end date
            $startDate = Carbon::parse($data['start_date']);
            $endDate = $startDate->copy()->addWeeks($totalWeeks);

            // Create program
            $program = WeeklyProgram::create([
                'clinic_id' => $data['clinic_id'],
                'patient_id' => $data['patient_id'],
                'episode_id' => $data['episode_id'] ?? null,
                'therapist_id' => $data['therapist_id'] ?? null,
                'program_name' => $data['program_name'],
                'specialty' => $data['specialty'],
                'sessions_per_week' => $sessionsPerWeek,
                'total_weeks' => $totalWeeks,
                'total_sessions' => $totalSessions,
                'session_types' => $data['session_types'] ?? null,
                'progression_rules' => $data['progression_rules'] ?? null,
                'reassessment_interval_weeks' => $data['reassessment_interval_weeks'] ?? 4,
                'reassessment_schedule' => $this->calculateReassessmentSchedule($totalWeeks, $data['reassessment_interval_weeks'] ?? 4),
                'payment_plan' => $data['payment_plan'] ?? 'pay_per_week',
                'total_price' => $pricingData['total_with_discount'],
                'discount_percentage' => $pricingData['discount_percentage'],
                'weekly_price' => $pricingData['weekly_price'],
                'monthly_price' => $pricingData['monthly_price'],
                'paid_amount' => 0,
                'remaining_balance' => $pricingData['total_with_discount'],
                'status' => 'draft',
                'start_date' => $startDate,
                'end_date' => $endDate,
                'auto_booking_enabled' => $data['auto_booking_enabled'] ?? true,
                'preferred_times' => $data['preferred_times'] ?? null,
                'preferred_days' => $data['preferred_days'] ?? null,
                'notes' => $data['notes'] ?? null,
                'goals' => $data['goals'] ?? null
            ]);

            // Generate program sessions
            $this->generateProgramSessions($program, $pricingData['single_session_price']);

            DB::commit();

            Log::info('Weekly program created', [
                'program_id' => $program->id,
                'clinic_id' => $program->clinic_id,
                'patient_id' => $program->patient_id
            ]);

            return $program;

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to create weekly program', [
                'error' => $e->getMessage(),
                'data' => $data
            ]);
            throw $e;
        }
    }

    /**
     * Generate program sessions
     * 
     * @param WeeklyProgram $program
     * @param float $sessionPrice
     * @return void
     */
    protected function generateProgramSessions(WeeklyProgram $program, float $sessionPrice): void
    {
        $startDate = Carbon::parse($program->start_date);
        $sessionsPerWeek = (int) $program->sessions_per_week;
        $totalWeeks = (int) $program->total_weeks;
        $sessionInProgram = 0;

        // Get preferred days if set
        $preferredDays = $program->preferred_days ?? $this->getDefaultDays($sessionsPerWeek);
        $preferredTimes = $program->preferred_times ?? ['09:00', '10:00', '11:00', '14:00', '15:00'];

        for ($week = 1; $week <= $totalWeeks; $week++) {
            $weekStartDate = $startDate->copy()->addWeeks($week - 1)->startOfWeek();

            for ($session = 1; $session <= $sessionsPerWeek; $session++) {
                $sessionInProgram++;

                // Determine session type
                $sessionType = $this->determineSessionType($week, $session, $program);

                // Calculate date (use preferred days)
                $dayIndex = ($session - 1) % count($preferredDays);
                $dayOffset = $this->getDayOffset($preferredDays[$dayIndex]);
                $scheduledDate = $weekStartDate->copy()->addDays($dayOffset);

                // Get time
                $timeIndex = ($session - 1) % count($preferredTimes);
                $scheduledTime = $preferredTimes[$timeIndex];

                ProgramSession::create([
                    'program_id' => $program->id,
                    'scheduled_date' => $scheduledDate,
                    'scheduled_time' => $scheduledTime,
                    'week_number' => $week,
                    'session_number' => $session,
                    'session_in_program' => $sessionInProgram,
                    'session_type' => $sessionType,
                    'status' => 'scheduled',
                    'session_price' => $sessionPrice
                ]);
            }
        }
    }

    /**
     * Determine session type based on week and program rules
     */
    protected function determineSessionType(int $week, int $session, WeeklyProgram $program): string
    {
        // First session is always evaluation
        if ($week === 1 && $session === 1) {
            return 'evaluation';
        }

        // Check if this is a reassessment week
        $reassessmentSchedule = $program->reassessment_schedule ?? [];
        if (in_array($week, $reassessmentSchedule)) {
            return 're_evaluation';
        }

        // Last session might be discharge
        if ($week === $program->total_weeks && $session === $program->sessions_per_week) {
            return 'discharge';
        }

        // Default to followup
        return 'followup';
    }

    /**
     * Calculate reassessment schedule
     */
    protected function calculateReassessmentSchedule(int $totalWeeks, int $interval): array
    {
        $schedule = [];
        for ($week = $interval; $week <= $totalWeeks; $week += $interval) {
            $schedule[] = $week;
        }
        return $schedule;
    }

    /**
     * Get default days based on sessions per week
     */
    protected function getDefaultDays(int $sessionsPerWeek): array
    {
        return match($sessionsPerWeek) {
            1 => ['monday'],
            2 => ['monday', 'wednesday'],
            3 => ['monday', 'wednesday', 'friday'],
            4 => ['monday', 'tuesday', 'thursday', 'friday'],
            5 => ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'],
            default => ['monday', 'wednesday', 'friday']
        };
    }

    /**
     * Get day offset from day name
     */
    protected function getDayOffset(string $dayName): int
    {
        $days = [
            'sunday' => 0,
            'monday' => 1,
            'tuesday' => 2,
            'wednesday' => 3,
            'thursday' => 4,
            'friday' => 5,
            'saturday' => 6
        ];

        return $days[strtolower($dayName)] ?? 1;
    }

    /**
     * Auto-book sessions for a program
     * 
     * @param WeeklyProgram $program
     * @return int Number of sessions booked
     */
    public function autoBookSessions(WeeklyProgram $program): int
    {
        if (!$program->auto_booking_enabled) {
            return 0;
        }

        $bookedCount = 0;
        $sessions = $program->sessions()
            ->where('status', 'scheduled')
            ->where('scheduled_date', '>=', now())
            ->orderBy('scheduled_date')
            ->get();

        foreach ($sessions as $session) {
            try {
                // Create appointment
                $appointment = ClinicAppointment::create([
                    'clinic_id' => $program->clinic_id,
                    'patient_id' => $program->patient_id,
                    'doctor_id' => $program->therapist_id,
                    'appointment_date' => Carbon::parse($session->scheduled_date)
                        ->setTimeFromTimeString($session->scheduled_time ?? '09:00'),
                    'duration_minutes' => 60, // Default, should be configurable
                    'status' => 'scheduled',
                    'visit_type' => $session->session_type,
                    'location' => 'clinic', // Default
                    'specialty' => $program->specialty,
                    'session_type' => $session->session_type
                ]);

                // Link session to appointment
                $session->update([
                    'appointment_id' => $appointment->id,
                    'status' => 'booked'
                ]);

                $bookedCount++;

            } catch (\Exception $e) {
                Log::error('Failed to auto-book session', [
                    'session_id' => $session->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        return $bookedCount;
    }

    /**
     * Activate program and auto-book sessions
     * 
     * @param WeeklyProgram $program
     * @return WeeklyProgram
     */
    public function activateProgram(WeeklyProgram $program): WeeklyProgram
    {
        $program->update(['status' => 'active']);

        if ($program->auto_booking_enabled) {
            $this->autoBookSessions($program);
        }

        return $program->fresh();
    }
}

