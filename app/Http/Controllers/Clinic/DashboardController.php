<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use App\Models\Patient;
use App\Models\ClinicAppointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $clinic = Clinic::where('company_id', Auth::id())->first();
        
        if (!$clinic) {
            return view('web.clinic.no_clinic');
        }

        $stats = [
            'total_patients' => Patient::where('clinic_id', $clinic->id)->count(),
            'total_appointments' => ClinicAppointment::where('clinic_id', $clinic->id)->count(),
            'today_appointments' => ClinicAppointment::where('clinic_id', $clinic->id)
                ->whereDate('appointment_date', today())
                ->count()
        ];

        return view('web.clinic.dashboard', compact('clinic', 'stats'));
    }
}
