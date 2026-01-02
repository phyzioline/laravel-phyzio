<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClinicRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role  The required role (doctor, receptionist, accountant, admin)
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Admin has full access
        if ($user->hasRole('admin') || $user->type === 'company') {
            return $next($request);
        }

        // Check clinic staff role
        $clinic = $this->getUserClinic($user);
        if (!$clinic) {
            abort(403, 'Clinic not found');
        }

        // Get user's role in clinic
        $staffRole = $this->getClinicStaffRole($user, $clinic);
        
        // Role-based access control
        switch ($role) {
            case 'doctor':
                // Doctors can access: medical data, assessments, clinical notes, patient medical info
                if (!in_array($staffRole, ['doctor', 'therapist', 'admin'])) {
                    abort(403, 'Access denied. Doctors and therapists only.');
                }
                // Block access to financial data
                if ($this->isFinancialRoute($request)) {
                    abort(403, 'Doctors cannot access financial data.');
                }
                break;

            case 'receptionist':
                // Receptionists can access: patients, appointments, payments (add only)
                if (!in_array($staffRole, ['receptionist', 'admin'])) {
                    abort(403, 'Access denied. Receptionists only.');
                }
                // Block access to clinical notes and assessments
                if ($this->isClinicalDataRoute($request)) {
                    abort(403, 'Receptionists cannot access clinical data.');
                }
                // Block access to financial reports
                if ($this->isFinancialReportRoute($request)) {
                    abort(403, 'Receptionists cannot access financial reports.');
                }
                break;

            case 'accountant':
                // Accountants can access: financial reports, invoices, payments, expenses
                if (!in_array($staffRole, ['accountant', 'admin'])) {
                    abort(403, 'Access denied. Accountants only.');
                }
                // Block access to clinical data
                if ($this->isClinicalDataRoute($request)) {
                    abort(403, 'Accountants cannot access clinical data.');
                }
                break;

            case 'admin':
                // Clinic admin has full access
                if ($staffRole !== 'admin') {
                    abort(403, 'Access denied. Admin only.');
                }
                break;
        }

        return $next($request);
    }

    /**
     * Get user's clinic
     */
    protected function getUserClinic($user)
    {
        try {
            return \App\Models\Clinic::where('company_id', $user->id)->first();
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Get user's role in clinic
     */
    protected function getClinicStaffRole($user, $clinic)
    {
        if (\Schema::hasTable('clinic_staff')) {
            $staff = \DB::table('clinic_staff')
                ->where('clinic_id', $clinic->id)
                ->where('user_id', $user->id)
                ->where('is_active', true)
                ->first();
            
            return $staff->role ?? 'therapist'; // Default to therapist
        }
        
        // Fallback: check user type
        return $user->type === 'therapist' ? 'doctor' : 'receptionist';
    }

    /**
     * Check if route is for financial data
     */
    protected function isFinancialRoute(Request $request): bool
    {
        $financialRoutes = [
            'clinic.invoices',
            'clinic.payments',
            'clinic.expenses',
            'clinic.reports',
            'clinic.billing'
        ];

        $routeName = $request->route()?->getName();
        
        foreach ($financialRoutes as $route) {
            if (str_starts_with($routeName, $route)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if route is for clinical data
     */
    protected function isClinicalDataRoute(Request $request): bool
    {
        $clinicalRoutes = [
            'clinic.assessments',
            'clinic.clinical-notes',
            'clinic.episodes'
        ];

        $routeName = $request->route()?->getName();
        
        foreach ($clinicalRoutes as $route) {
            if (str_starts_with($routeName, $route)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if route is for financial reports
     */
    protected function isFinancialReportRoute(Request $request): bool
    {
        $routeName = $request->route()?->getName();
        return str_starts_with($routeName, 'clinic.reports');
    }
}

