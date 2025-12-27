<?php

namespace App\Http\Controllers\Therapist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\HomeVisit;

class PatientController extends Controller
{
    public function index()
    {
        // Get unique patients from home visits
        $patientIds = HomeVisit::where('therapist_id', Auth::id())
            ->whereNotNull('patient_id')
            ->distinct()
            ->pluck('patient_id')
            ->toArray();

        // If no patients, return empty collection
        if (empty($patientIds)) {
            $patients = collect();
        } else {
            // Get patient users with their latest visit
            $patients = \App\Models\User::whereIn('id', $patientIds)
                ->get()
                ->map(function($user) {
                    // Get latest visit for this patient with this therapist
                    $latestVisit = HomeVisit::where('patient_id', $user->id)
                        ->where('therapist_id', Auth::id())
                        ->orderBy('scheduled_at', 'desc')
                        ->first();
                    
                    // Determine status based on latest visit
                    $status = 'Stable';
                    if ($latestVisit) {
                        if ($latestVisit->status == 'completed') {
                            $status = 'Stable';
                        } elseif (in_array($latestVisit->status, ['pending', 'requested'])) {
                            $status = 'Needs Follow-up';
                        } elseif ($latestVisit->urgency == 'urgent') {
                            $status = 'Critical';
                        }
                    }
                    
                    return (object)[
                        'id' => '#P' . str_pad($user->id, 6, '0', STR_PAD_LEFT),
                        'name' => $user->name,
                        'age' => $user->age ?? 'N/A',
                        'gender' => $user->gender ?? 'N/A',
                        'last_visit' => $latestVisit && $latestVisit->scheduled_at ? $latestVisit->scheduled_at->format('M d, Y') : 'N/A',
                        'status' => $status,
                        'conditions' => $latestVisit && $latestVisit->complain_type ? [$latestVisit->complain_type] : [],
                        'image_initial' => strtoupper(substr($user->name, 0, 1)),
                        'user_id' => $user->id
                    ];
                });
        }

        // Calculate stats
        $totalPatients = $patients->count();
        $newThisMonth = $patients->filter(function($p) {
            // Check if patient has a visit this month
            $visit = HomeVisit::where('patient_id', $p->user_id)
                ->where('therapist_id', Auth::id())
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->first();
            return $visit !== null;
        })->count();
        
        $needFollowup = $patients->where('status', 'Needs Follow-up')->count();
        $criticalCases = $patients->where('status', 'Critical')->count();

        return view('web.therapist.patients.index', compact('patients', 'totalPatients', 'newThisMonth', 'needFollowup', 'criticalCases'));
    }

    public function create()
    {
        return view('web.therapist.patients.create');
    }

    public function show($id)
    {
        // Get patient user
        $user = \App\Models\User::findOrFail($id);
        
        // Get all visits for this patient with this therapist
        $visits = HomeVisit::where('patient_id', $user->id)
            ->where('therapist_id', Auth::id())
            ->orderBy('scheduled_at', 'desc')
            ->get();
        
        $latestVisit = $visits->first();
        
        $patient = (object)[
            'id' => $id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone ?? 'N/A',
            'age' => $user->age ?? 'N/A',
            'gender' => $user->gender ?? 'N/A',
            'condition' => $latestVisit ? ($latestVisit->complain_type ?? 'N/A') : 'N/A',
            'status' => $latestVisit ? ucfirst($latestVisit->status) : 'No visits',
            'last_visit' => $latestVisit ? $latestVisit->scheduled_at->format('Y-m-d') : 'N/A',
            'next_visit' => $visits->where('status', 'scheduled')->first() ? $visits->where('status', 'scheduled')->first()->scheduled_at->format('Y-m-d') : 'N/A',
            'image' => $user->profile_photo_url ?? asset('dashboard/images/Frame 127.svg'),
            'visits' => $visits
        ];
        
        return view('web.therapist.patients.show', compact('patient'));
    }
}
