<?php

namespace App\Http\Controllers\Clinic;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\DoctorWorkLog;

class DoctorHoursReportController extends BaseClinicController
{
    /**
     * Show doctor hours reports
     */
    public function index(Request $request)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return redirect()->route('clinic.dashboard')
                ->with('error', 'Clinic not found.');
        }
        
        // Get filter parameters
        $period = $request->get('period', 'daily'); // daily, weekly, monthly
        $date = $request->get('date', now()->toDateString());
        $doctorId = $request->get('doctor_id');
        
        $selectedDate = Carbon::parse($date);
        
        // Get date range based on period
        switch ($period) {
            case 'weekly':
                $startDate = $selectedDate->copy()->startOfWeek();
                $endDate = $selectedDate->copy()->endOfWeek();
                break;
            case 'monthly':
                $startDate = $selectedDate->copy()->startOfMonth();
                $endDate = $selectedDate->copy()->endOfMonth();
                break;
            default: // daily
                $startDate = $selectedDate->copy();
                $endDate = $selectedDate->copy();
                break;
        }
        
        // Build query
        $query = DoctorWorkLog::where('clinic_id', $clinic->id)
            ->whereBetween('work_date', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->with(['doctor', 'patient', 'appointment']);
        
        if ($doctorId) {
            $query->where('doctor_id', $doctorId);
        }
        
        $workLogs = $query->orderBy('work_date')
            ->orderBy('start_time')
            ->get();
        
        // Group by doctor for summary
        $summaryByDoctor = $workLogs->groupBy('doctor_id')->map(function($logs, $doctorId) {
            $doctor = $logs->first()->doctor;
            return [
                'doctor' => $doctor,
                'total_hours' => $logs->sum('hours_worked'),
                'total_minutes' => $logs->sum('minutes_worked'),
                'total_earnings' => $logs->sum('total_amount'),
                'session_count' => $logs->count(),
                'avg_hourly_rate' => $logs->avg('hourly_rate'),
            ];
        });
        
        // Overall totals
        $totalHours = $workLogs->sum('hours_worked');
        $totalEarnings = $workLogs->sum('total_amount');
        $totalSessions = $workLogs->count();
        
        // Get all doctors for filter
        $doctors = \App\Models\User::whereHas('clinicStaff', function($q) use ($clinic) {
            $q->where('clinic_id', $clinic->id)
              ->whereIn('role', ['therapist', 'doctor'])
              ->where('is_active', true);
        })->get();
        
        return view('web.clinic.reports.doctor-hours', compact(
            'clinic',
            'period',
            'selectedDate',
            'startDate',
            'endDate',
            'workLogs',
            'summaryByDoctor',
            'totalHours',
            'totalEarnings',
            'totalSessions',
            'doctors',
            'doctorId'
        ));
    }

    /**
     * Export to Excel/CSV
     */
    public function export(Request $request)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return redirect()->back()->with('error', 'Clinic not found.');
        }
        
        $period = $request->get('period', 'daily');
        $date = $request->get('date', now()->toDateString());
        $doctorId = $request->get('doctor_id');
        $format = $request->get('format', 'csv'); // csv or pdf
        
        $selectedDate = Carbon::parse($date);
        
        switch ($period) {
            case 'weekly':
                $startDate = $selectedDate->copy()->startOfWeek();
                $endDate = $selectedDate->copy()->endOfWeek();
                break;
            case 'monthly':
                $startDate = $selectedDate->copy()->startOfMonth();
                $endDate = $selectedDate->copy()->endOfMonth();
                break;
            default:
                $startDate = $selectedDate->copy();
                $endDate = $selectedDate->copy();
                break;
        }
        
        $query = DoctorWorkLog::where('clinic_id', $clinic->id)
            ->whereBetween('work_date', [$startDate, $endDate])
            ->where('status', '!=', 'cancelled')
            ->with(['doctor', 'patient', 'appointment']);
        
        if ($doctorId) {
            $query->where('doctor_id', $doctorId);
        }
        
        $workLogs = $query->orderBy('work_date')
            ->orderBy('start_time')
            ->get();
        
        if ($format === 'csv') {
            return $this->exportToCsv($workLogs, $period, $startDate, $endDate);
        } else {
            return $this->exportToPdf($workLogs, $period, $startDate, $endDate, $clinic);
        }
    }

    private function exportToCsv($workLogs, $period, $startDate, $endDate)
    {
        $filename = 'doctor_hours_' . $period . '_' . $startDate->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($workLogs) {
            $file = fopen('php://output', 'w');
            
            // Header row
            fputcsv($file, [
                'Date',
                'Doctor',
                'Patient',
                'Start Time',
                'End Time',
                'Hours',
                'Minutes',
                'Hourly Rate',
                'Total Amount',
                'Session Type',
                'Specialty'
            ]);
            
            // Data rows
            foreach ($workLogs as $log) {
                fputcsv($file, [
                    $log->work_date->format('Y-m-d'),
                    $log->doctor->name ?? 'N/A',
                    $log->patient->first_name . ' ' . $log->patient->last_name ?? 'N/A',
                    $log->start_time->format('H:i'),
                    $log->end_time->format('H:i'),
                    $log->hours_worked,
                    $log->minutes_worked,
                    $log->hourly_rate,
                    $log->total_amount,
                    $log->session_type ?? 'N/A',
                    $log->specialty ?? 'N/A',
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    private function exportToPdf($workLogs, $period, $startDate, $endDate, $clinic)
    {
        // For PDF, we'll use a simple HTML view that can be printed
        // In production, you'd use a library like DomPDF or Snappy
        $summary = $workLogs->groupBy('doctor_id')->map(function($logs) {
            return [
                'doctor' => $logs->first()->doctor,
                'total_hours' => $logs->sum('hours_worked'),
                'total_earnings' => $logs->sum('total_amount'),
            ];
        });
        
        $totalHours = $workLogs->sum('hours_worked');
        $totalEarnings = $workLogs->sum('total_amount');
        
        return view('web.clinic.reports.doctor-hours-pdf', compact(
            'workLogs',
            'summary',
            'totalHours',
            'totalEarnings',
            'period',
            'startDate',
            'endDate',
            'clinic'
        ));
    }
}

