<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class CrmController extends Controller
{
    public function __construct()
    {
        // Check admin access in constructor
        if (!auth()->check() || (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('super-admin'))) {
            abort(403, 'Unauthorized. This section is restricted to administrators only.');
        }
    }

    public function index()
    {
        // Redirect to new dashboard
        return redirect()->route('dashboard.crm.dashboard');
    }

    public function dashboard()
    {
        // 1. User Stats
        $totalUsers = \App\Models\User::count();
        $newUsers = \App\Models\User::where('created_at', '>=', now()->subDays(30))->count();
        $activeUsers = \App\Models\User::where('status', 'active')->count(); // Assuming 'status' column exists
        
        // 2. User Distribution (Pie Chart)
        $userDistribution = \App\Models\User::selectRaw('type, count(*) as count')
            ->groupBy('type')
            ->pluck('count', 'type');

        // 3. New Members (List)
        $newMembers = \App\Models\User::latest()->take(5)->get();

        // 4. Form Submissions (Feedback)
        $feedbackCount = \App\Models\Feedback::count();
        $recentFeedbacks = \App\Models\Feedback::latest()->take(5)->get();

        // 5. Visitors (Mock/System data for now)
        // In a real scenario, this would come from a tracking table or Google Analytics
        $totalSessions = 1205; // Mock data matching specific visual style if needed, or derived from logs

        return view('dashboard.crm.dashboard', compact(
            'totalUsers', 
            'newUsers', 
            'activeUsers', 
            'userDistribution', 
            'newMembers',
            'feedbackCount',
            'recentFeedbacks',
            'totalSessions'
        ));
    }

    public function contacts()
    {
        $patients = Patient::with('clinic')->latest()->paginate(10);
        return view('dashboard.crm.index', compact('patients'));
    }

    public function show(Patient $patient)
    {
        return view('dashboard.crm.show', compact('patient'));
    }
    
    // Additional CRM features like notes or history can be added here
}
