<?php

namespace App\Http\Controllers\Clinic;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends BaseClinicController
{
    public function index()
    {
        $clinic = $this->getUserClinic();
        
        // Show empty state instead of redirecting
        if (!$clinic) {
            $monthlyRevenue = [];
            $monthlyLabels = [];
            $patientGrowth = [];
            $totalPatients = 0;
            $totalAppointments = 0;
            $activePrograms = 0;
            $returningPatients = 0;
            $newPatients = 0;
            $patientGrowthPercentage = 0;
            return view('web.clinic.analytics.index', compact(
                'monthlyRevenue', 'monthlyLabels', 'patientGrowth', 
                'totalPatients', 'totalAppointments', 'activePrograms',
                'returningPatients', 'newPatients', 'patientGrowthPercentage', 'clinic'
            ));
        }

        // Real monthly revenue data (last 6 months)
        $monthlyRevenue = [];
        $monthlyLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            // Use startOfMonth() before subMonths() to avoid 31st day issues
            $month = now()->startOfMonth()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            $monthlyLabels[] = $monthStart->format('M');
            
            // Get revenue from WeeklyPrograms (primary source - has clinic_id and paid_amount)
            $revenue = \App\Models\WeeklyProgram::where('clinic_id', $clinic->id)
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->sum('paid_amount') ?? 0;
            
            $monthlyRevenue[] = $revenue ?? 0;
        }

        // Patient growth (last 6 months) - aligning with monthlyLabels
        $patientGrowth = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = now()->startOfMonth()->subMonths($i);
            $monthStart = $month->copy()->startOfMonth();
            $monthEnd = $month->copy()->endOfMonth();
            
            $newPatientsCount = \App\Models\Patient::where('clinic_id', $clinic->id)
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->count();
            
            $patientGrowth[] = $newPatientsCount;
        }

        // Additional metrics
        $totalPatients = \App\Models\Patient::where('clinic_id', $clinic->id)->count();
        $totalAppointments = \App\Models\ClinicAppointment::where('clinic_id', $clinic->id)->count();
        $activePrograms = \App\Models\WeeklyProgram::where('clinic_id', $clinic->id)
            ->where('status', 'active')
            ->count();
        
        // Returning vs New patients (last 3 months)
        $threeMonthsAgo = now()->subMonths(3);
        $returningPatients = \App\Models\Patient::where('clinic_id', $clinic->id)
            ->where('created_at', '<', $threeMonthsAgo)
            ->whereHas('appointments', function($q) use ($threeMonthsAgo, $clinic) {
                $q->where('clinic_id', $clinic->id)
                  ->where('created_at', '>=', $threeMonthsAgo);
            })
            ->count();
        
        $newPatients = \App\Models\Patient::where('clinic_id', $clinic->id)
            ->where('created_at', '>=', $threeMonthsAgo)
            ->count();

        // Calculate patient growth percentage
        $previousPeriodPatients = \App\Models\Patient::where('clinic_id', $clinic->id)
            ->whereBetween('created_at', [now()->subMonths(6), now()->subMonths(3)])
            ->count();
        
        $patientGrowthPercentage = $previousPeriodPatients > 0 
            ? round((($newPatients - $previousPeriodPatients) / $previousPeriodPatients) * 100, 1) 
            : ($newPatients > 0 ? 100 : 0);

        // Additional metrics
        $completedAppointments = \App\Models\ClinicAppointment::where('clinic_id', $clinic->id)
            ->where('status', 'completed')
            ->count();
        
        $cancelledAppointments = \App\Models\ClinicAppointment::where('clinic_id', $clinic->id)
            ->where('status', 'cancelled')
            ->count();
        
        $completionRate = $totalAppointments > 0 
            ? round(($completedAppointments / $totalAppointments) * 100, 1) 
            : 0;
        
        // Revenue from programs
        $programRevenue = \App\Models\WeeklyProgram::where('clinic_id', $clinic->id)
            ->where('status', 'active')
            ->sum('paid_amount');
        
        // Total revenue from WeeklyPrograms (primary source)
        $totalRevenue = \App\Models\WeeklyProgram::where('clinic_id', $clinic->id)
            ->sum('paid_amount') ?? 0;
        
        // Average appointment value
        $avgAppointmentValue = $completedAppointments > 0 
            ? round($totalRevenue / $completedAppointments, 2) 
            : 0;
        
        // Specialty distribution
        $specialtyDistribution = \App\Models\ClinicAppointment::where('clinic_id', $clinic->id)
            ->whereNotNull('specialty')
            ->select('specialty', DB::raw('count(*) as count'))
            ->groupBy('specialty')
            ->get()
            ->pluck('count', 'specialty')
            ->toArray();
        
        // Appointment status distribution
        $statusDistribution = \App\Models\ClinicAppointment::where('clinic_id', $clinic->id)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();
        
        return view('web.clinic.analytics.index', compact(
            'monthlyRevenue', 
            'monthlyLabels',
            'patientGrowth', 
            'totalPatients',
            'totalAppointments',
            'activePrograms',
            'returningPatients',
            'newPatients',
            'patientGrowthPercentage',
            'completedAppointments',
            'cancelledAppointments',
            'completionRate',
            'totalRevenue',
            'programRevenue',
            'avgAppointmentValue',
            'specialtyDistribution',
            'statusDistribution',
            'clinic'
        ));
    }
}
