<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends BaseClinicController
{
    /**
     * Display activity logs
     */
    public function index(Request $request)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return redirect()->route('clinic.dashboard')
                ->with('error', __('Clinic not found'));
        }

        $query = ActivityLog::where('clinic_id', $clinic->id)
            ->with(['user', 'model'])
            ->latest();

        // Filters
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(50);
        
        // Get users for filter
        $users = \App\Models\User::whereHas('roles', function($q) {
            // Get clinic staff users
        })->get();

        return view('web.clinic.activity-logs.index', compact('logs', 'users', 'clinic'));
    }

    /**
     * Show activity log details
     */
    public function show($id)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            abort(404);
        }

        $log = ActivityLog::where('clinic_id', $clinic->id)
            ->with(['user', 'model'])
            ->findOrFail($id);

        return view('web.clinic.activity-logs.show', compact('log', 'clinic'));
    }
}

