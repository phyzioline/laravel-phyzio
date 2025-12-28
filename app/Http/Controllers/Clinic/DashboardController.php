<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Services\Clinic\SpecialtySelectionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
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

        if ($clinic) {
            try {
                // Get REAL data filtered by clinic
                $totalPatients = \App\Models\Patient::where('clinic_id', $clinic->id)->count();
                
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
                if (\Schema::hasTable('payments')) {
                    $monthlyRevenue = \DB::table('payments')
                        ->where('created_at', '>=', now()->startOfMonth())
                        ->where('created_at', '<=', now()->endOfMonth())
                        ->where('status', 'paid')
                        ->sum('amount');
                }
                
                // Outstanding payments
                if (\Schema::hasTable('invoices')) {
                    $outstandingPayments = \DB::table('invoices')
                        ->where('clinic_id', $clinic->id)
                        ->where('status', 'pending')
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
        }

        // Recent activities (from new features)
        $recentActivities = $this->getRecentActivities($clinic);

        return view('web.clinic.dashboard', compact(
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
            'recentActivities'
        ));
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

    /**
     * Get user's clinic
     * Adjust this method based on your actual user-clinic relationship
     */
    protected function getUserClinic($user)
    {
        // Option 1: User has direct clinic relationship
        if (method_exists($user, 'clinic') && $user->clinic) {
            return $user->clinic;
        }

        // Option 2: User is company and has clinics
        if ($user->type === 'company' && method_exists($user, 'clinics')) {
            return $user->clinics()->first();
        }

        // Option 3: Find clinic by company_id matching user_id
        return Clinic::where('company_id', $user->id)->first();
    }
}
