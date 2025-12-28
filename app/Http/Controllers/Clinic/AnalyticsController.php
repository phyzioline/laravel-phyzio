<?php

namespace App\Http\Controllers\Clinic;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends BaseClinicController
{
    public function index()
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return redirect()->route('clinic.dashboard')
                ->with('error', 'Clinic not found.');
        }

        // Real monthly revenue data (last 6 months)
        $monthlyRevenue = [];
        $monthlyLabels = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthStart = now()->subMonths($i)->startOfMonth();
            $monthEnd = now()->subMonths($i)->endOfMonth();
            $monthlyLabels[] = $monthStart->format('M');
            
            // Get revenue from payments or invoices
            if (\Schema::hasTable('payments')) {
                $revenue = DB::table('payments')
                    ->where('clinic_id', $clinic->id)
                    ->whereBetween('created_at', [$monthStart, $monthEnd])
                    ->where('status', 'paid')
                    ->sum('amount');
            } else {
                $revenue = 0;
            }
            
            $monthlyRevenue[] = $revenue ?? 0;
        }

        // Patient growth (last 6 months)
        $patientGrowth = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthStart = now()->subMonths($i)->startOfMonth();
            $monthEnd = now()->subMonths($i)->endOfMonth();
            
            $newPatients = \App\Models\Patient::where('clinic_id', $clinic->id)
                ->whereBetween('created_at', [$monthStart, $monthEnd])
                ->count();
            
            $patientGrowth[] = $newPatients;
        }

        // Additional metrics
        $totalPatients = \App\Models\Patient::where('clinic_id', $clinic->id)->count();
        $totalAppointments = \App\Models\ClinicAppointment::where('clinic_id', $clinic->id)->count();
        $activePrograms = \App\Models\WeeklyProgram::where('clinic_id', $clinic->id)
            ->where('status', 'active')
            ->count();
        
        // Returning vs New patients
        $returningPatients = \App\Models\Patient::where('clinic_id', $clinic->id)
            ->whereHas('appointments', function($q) {
                $q->where('created_at', '>=', now()->subMonths(3));
            })
            ->count();
        
        $newPatients = \App\Models\Patient::where('clinic_id', $clinic->id)
            ->where('created_at', '>=', now()->subMonths(3))
            ->count();

        $patientGrowthPercentage = $totalPatients > 0 
            ? round(($newPatients / $totalPatients) * 100, 1) 
            : 0;
        
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
            'clinic'
        ));
    }
}
