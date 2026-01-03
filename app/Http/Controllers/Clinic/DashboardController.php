<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Services\Clinic\SpecialtySelectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardController extends BaseClinicController
{
    protected $specialtySelectionService;

    public function __construct(SpecialtySelectionService $specialtySelectionService)
    {
        $this->specialtySelectionService = $specialtySelectionService;
    }

    public function index()
    {
        $user = Auth::user();
        
        // Check if clinic needs specialty selection
        $clinic = $this->getUserClinic($user);
        if ($clinic && $this->specialtySelectionService->needsSpecialtySelection($clinic)) {
            return redirect()->route('clinic.specialty-selection.show')
                ->with('info', 'Please select your physical therapy specialty to continue.');
        }
        
        // Check if onboarding is needed (only if not skipped and not already completed)
        // Use the same logic as OnboardingController to avoid redirect loops
        if ($clinic && !$clinic->onboarding_skipped && !$clinic->onboarding_completed) {
            // Check if onboarding is actually complete (even if flag isn't set)
            $onboardingComplete = $clinic->hasSelectedSpecialty() && 
                                  $clinic->name && 
                                  $clinic->phone;
            
            if (!$onboardingComplete) {
                return redirect()->route('clinic.onboarding.index')
                    ->with('info', 'Complete the setup wizard to get started.');
            } else {
                // Auto-complete onboarding if requirements are met
                $clinic->update([
                    'onboarding_completed' => true,
                    'onboarding_completed_at' => now()
                ]);
            }
        }

        // Initialize with zeros
        $totalPatients = 0;
        $activePlans = 0;
        $todayAppointments = 0;
        $completedToday = 0;
        $outstandingPayments = 0;
        $timeline = collect();
        $activePrograms = 0;
        $totalPrograms = 0;
        $monthlyRevenue = 0;
        $activeDoctors = 0;
        
        // NEW: Today's metrics
        $todayPatients = 0;
        $todaySessions = 0;
        $todayIncome = 0;
        $todayPatientsList = collect();

        if ($clinic) {
            try {
                // Cache key for dashboard metrics (5 minutes cache)
                $cacheKey = "clinic_dashboard_{$clinic->id}_" . now()->format('Y-m-d-H-i');
                
                // Try to get from cache first
                $cachedData = Cache::get($cacheKey);
                if ($cachedData && false) { // Temporarily disable cache for testing
                    return view('web.clinic.dashboard', array_merge($cachedData, [
                        'clinic' => $clinic,
                        'clinicSpecialty' => $clinic->primary_specialty ?? null,
                        'specialtyDisplayName' => $clinic->getPrimarySpecialtyDisplayName() ?? 'General'
                    ]));
                }
                
                // Get REAL data filtered by clinic
                $totalPatients = \App\Models\Patient::where('clinic_id', $clinic->id)->count();
                
                // Today's Patients (patients with appointments today)
                $todayPatientsQuery = \App\Models\ClinicAppointment::where('clinic_id', $clinic->id)
                    ->whereDate('appointment_date', today())
                    ->with('patient')
                    ->distinct('patient_id');
                $todayPatients = $todayPatientsQuery->count('patient_id');
                $todayPatientsList = \App\Models\ClinicAppointment::where('clinic_id', $clinic->id)
                    ->whereDate('appointment_date', today())
                    ->with('patient')
                    ->get()
                    ->pluck('patient')
                    ->unique('id')
                    ->take(5);
                
                // Today's Sessions (completed appointments today)
                $todaySessions = \App\Models\ClinicAppointment::where('clinic_id', $clinic->id)
                    ->whereDate('appointment_date', today())
                    ->where('status', 'completed')
                    ->count();
                
                // Today's Income (from payments made today)
                if (\Schema::hasTable('patient_payments')) {
                    $todayIncome = \App\Models\PatientPayment::where('clinic_id', $clinic->id)
                        ->whereDate('payment_date', today())
                        ->sum('payment_amount');
                } elseif (\Schema::hasTable('payments')) {
                    $todayIncome = \DB::table('payments')
                        ->where('clinic_id', $clinic->id)
                        ->whereDate('created_at', today())
                        ->where('status', 'paid')
                        ->sum('amount');
                }
                
                // Active treatment plans
                if (\Schema::hasTable('treatment_plans')) {
                    $activePlans = \DB::table('treatment_plans')
                        ->where('clinic_id', $clinic->id)
                        ->where('status', 'active')
                        ->count();
                }
                
                // Weekly Programs (NEW FEATURE)
                if (\Schema::hasTable('weekly_programs')) {
                    $totalPrograms = \App\Models\WeeklyProgram::where('clinic_id', $clinic->id)->count();
                    $activePrograms = \App\Models\WeeklyProgram::where('clinic_id', $clinic->id)
                        ->where('status', 'active')
                        ->count();
                }
                
                // Today's appointments (REAL DATA)
                $todayAppointments = \App\Models\ClinicAppointment::where('clinic_id', $clinic->id)
                    ->whereDate('appointment_date', today())
                    ->count();
                    
                $completedToday = \App\Models\ClinicAppointment::where('clinic_id', $clinic->id)
                    ->whereDate('appointment_date', today())
                    ->where('status', 'completed')
                    ->count();
                
                // Upcoming appointments timeline
                $timeline = \App\Models\ClinicAppointment::where('clinic_id', $clinic->id)
                    ->with(['patient', 'doctor'])
                    ->where('appointment_date', '>=', now())
                    ->where('status', '!=', 'cancelled')
                    ->orderBy('appointment_date', 'asc')
                    ->take(5)
                    ->get();
                
                // Active doctors/therapists
                $activeDoctors = \App\Models\User::where('type', 'therapist')
                    ->whereHas('therapistProfile', function($q) use ($clinic) {
                        // Filter by clinic if relationship exists
                    })
                    ->count();
                
                // Monthly revenue (from appointments or payments)
                if (\Schema::hasTable('patient_payments')) {
                    $monthlyRevenue = \App\Models\PatientPayment::where('clinic_id', $clinic->id)
                        ->whereMonth('payment_date', now()->month)
                        ->whereYear('payment_date', now()->year)
                        ->sum('payment_amount');
                } elseif (\Schema::hasTable('payments')) {
                    $monthlyRevenue = \DB::table('payments')
                        ->where('created_at', '>=', now()->startOfMonth())
                        ->where('created_at', '<=', now()->endOfMonth())
                        ->where('status', 'paid')
                        ->sum('amount');
                }
                
                // Outstanding payments (unpaid invoices)
                if (\Schema::hasTable('patient_invoices')) {
                    $outstandingPayments = \App\Models\PatientInvoice::where('clinic_id', $clinic->id)
                        ->whereIn('status', ['unpaid', 'partially_paid'])
                        ->get()
                        ->sum(function($invoice) {
                            return $invoice->remaining_balance ?? ($invoice->final_amount - $invoice->total_paid);
                        });
                } elseif (\Schema::hasTable('invoices')) {
                    $outstandingPayments = \DB::table('invoices')
                        ->where('clinic_id', $clinic->id)
                        ->whereIn('status', ['pending', 'unpaid', 'partially_paid'])
                        ->sum('amount');
                }
                
            } catch (\Exception $e) {
                \Log::error('Dashboard data fetch error', [
                    'clinic_id' => $clinic->id ?? null,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                // Keep zeros if error
            }
        }

        // Monthly performance data (last 4 weeks)
        $weeklyData = [];
        $weeklyLabels = [];
        for ($i = 3; $i >= 0; $i--) {
            $weekStart = now()->subWeeks($i)->startOfWeek();
            $weekEnd = now()->subWeeks($i)->endOfWeek();
            $weeklyLabels[] = 'Week ' . (4 - $i);
            
            if ($clinic) {
                $weekAppointments = \App\Models\ClinicAppointment::where('clinic_id', $clinic->id)
                    ->whereBetween('appointment_date', [$weekStart, $weekEnd])
                    ->count();
                $weeklyData[] = $weekAppointments;
            } else {
                $weeklyData[] = 0;
            }
        }

        $monthlyPerformance = [
            'labels' => $weeklyLabels,
            'data' => $weeklyData
        ];

        // Get clinic specialty info
        $clinicSpecialty = null;
        $specialtyDisplayName = null;
        if ($clinic) {
            $clinicSpecialty = $clinic->primarySpecialty;
            $specialtyDisplayName = $clinic->getPrimarySpecialtyDisplayName();
            
            // Reload clinic to ensure relationships are loaded
            $clinic->load('primarySpecialty');
        }

        // Recent activities (from new features)
        $recentActivities = $this->getRecentActivities($clinic);

        // Net Profit Indicator (Current Month)
        $currentMonthProfit = 0;
        $currentMonthExpenses = 0;
        if ($clinic) {
            try {
                if (\Schema::hasTable('daily_expenses')) {
                    $currentMonthExpenses = \DB::table('daily_expenses')
                        ->where('clinic_id', $clinic->id)
                        ->whereMonth('expense_date', now()->month)
                        ->whereYear('expense_date', now()->year)
                        ->sum('amount');
                }
                
                $currentMonthProfit = $monthlyRevenue - $currentMonthExpenses;
            } catch (\Exception $e) {
                \Log::error('Error calculating profit', ['error' => $e->getMessage()]);
            }
        }

        // Prepare view data
        $viewData = compact(
            'totalPatients', 
            'activePlans', 
            'todayAppointments', 
            'completedToday', 
            'outstandingPayments',
            'timeline',
            'monthlyPerformance',
            'clinic',
            'clinicSpecialty',
            'specialtyDisplayName',
            'activePrograms',
            'totalPrograms',
            'monthlyRevenue',
            'activeDoctors',
            'recentActivities',
            'todayPatients',
            'todaySessions',
            'todayIncome',
            'todayPatientsList',
            'currentMonthProfit',
            'currentMonthExpenses'
        );
        
        // Cache the dashboard data for 5 minutes
        if (isset($cacheKey)) {
            Cache::put($cacheKey, $viewData, now()->addMinutes(5));
        }
        
        return view('web.clinic.dashboard', $viewData);
    }

    /**
     * Get recent activities from new features
     */
    protected function getRecentActivities($clinic)
    {
        $activities = collect();
        
        if (!$clinic) {
            return $activities;
        }

        try {
            // Recent programs
            $recentPrograms = \App\Models\WeeklyProgram::where('clinic_id', $clinic->id)
                ->latest()
                ->take(3)
                ->get()
                ->map(function($program) {
                    return (object)[
                        'type' => 'program',
                        'icon' => 'las la-clipboard-list',
                        'color' => 'primary',
                        'title' => 'New Treatment Program Created',
                        'description' => $program->program_name . ' for ' . ($program->patient->first_name ?? 'Patient'),
                        'time' => $program->created_at->diffForHumans(),
                        'link' => route('clinic.programs.show', $program->id)
                    ];
                });
            $activities = $activities->merge($recentPrograms);

            // Recent appointments
            $recentAppointments = \App\Models\ClinicAppointment::where('clinic_id', $clinic->id)
                ->with('patient')
                ->latest()
                ->take(3)
                ->get()
                ->map(function($appointment) {
                    return (object)[
                        'type' => 'appointment',
                        'icon' => 'las la-calendar-check',
                        'color' => 'info',
                        'title' => 'New Appointment Scheduled',
                        'description' => ($appointment->patient->first_name ?? 'Patient') . ' - ' . ($appointment->specialty ? ucfirst($appointment->specialty) : 'Appointment'),
                        'time' => $appointment->created_at->diffForHumans(),
                        'link' => route('clinic.appointments.index')
                    ];
                });
            $activities = $activities->merge($recentAppointments);

            // Sort by time and take latest 5
            $activities = $activities->sortByDesc(function($activity) {
                return $activity->time;
            })->take(5);

        } catch (\Exception $e) {
            \Log::error('Error fetching recent activities', ['error' => $e->getMessage()]);
        }

        return $activities;
    }

}
