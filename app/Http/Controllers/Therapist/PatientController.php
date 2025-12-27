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
            ->select('patient_id')
            ->distinct()
            ->pluck('patient_id');

        // Get patient users with their latest visit
        $patients = \App\Models\User::whereIn('id', $patientIds)
            ->with(['homeVisits' => function($query) {
                $query->where('therapist_id', Auth::id())
                      ->orderBy('scheduled_at', 'desc')
                      ->limit(1);
            }])
            ->get()
            ->map(function($user) {
                $latestVisit = $user->homeVisits->first();
                $age = $user->age ?? null;
                
                // Determine status based on latest visit
                $status = 'Stable';
                if ($latestVisit) {
                    if ($latestVisit->status == 'completed') {
                        $status = 'Stable';
                    } elseif ($latestVisit->status == 'pending' || $latestVisit->status == 'requested') {
                        $status = 'Needs Follow-up';
                    } elseif ($latestVisit->urgency == 'urgent') {
                        $status = 'Critical';
                    }
                }
                
                return (object)[
                    'id' => '#P' . str_pad($user->id, 6, '0', STR_PAD_LEFT),
                    'name' => $user->name,
                    'age' => $age ?? 'N/A',
                    'gender' => $user->gender ?? 'N/A',
                    'last_visit' => $latestVisit ? $latestVisit->scheduled_at->format('M d, Y') : 'N/A',
                    'status' => $status,
                    'conditions' => $latestVisit && $latestVisit->complain_type ? [$latestVisit->complain_type] : [],
                    'image_initial' => strtoupper(substr($user->name, 0, 1)),
                    'user_id' => $user->id
                ];
            });

        return view('web.therapist.patients.index', compact('patients'));
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
