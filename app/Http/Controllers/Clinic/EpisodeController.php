<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use App\Models\EpisodeOfCare;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EpisodeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display list of active episodes for this clinic.
     */
    public function index()
    {
        $episodes = EpisodeOfCare::where('clinic_id', Auth::id())
                        ->with('patient', 'primaryTherapist')
                        ->orderBy('status')
                        ->latest()
                        ->get();
                        
        return view('clinic.erp.episodes.index', compact('episodes'));
    }

    /**
     * Show form to create new episode (Start of Care).
     */
    public function create()
    {
        $patients = User::where('type', 'patient')->get(); // In real app, filter for this clinic
        $therapists = User::where('type', 'therapist')->get();
        return view('clinic.erp.episodes.create', compact('patients', 'therapists'));
    }

    /**
     * Store new episode.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:users,id',
            'primary_therapist_id' => 'required|exists:users,id',
            'specialty' => 'required|string',
            'start_date' => 'required|date',
            'chief_complaint' => 'required|string',
            'diagnosis_icd' => 'nullable|string',
        ]);

        $data['clinic_id'] = Auth::id();
        $data['status'] = 'active';

        $episode = EpisodeOfCare::create($data);

        return redirect()->route('clinic.episodes.show', $episode)->with('success', 'Episode of Care created.');
    }

    /**
     * Show the Clinical Dashboard for this Episode.
     * This is the heart of the ERP.
     */
    public function show(EpisodeOfCare $episode)
    {
        $episode->load(['assessments', 'treatments', 'outcomes']);
        return view('clinic.erp.episodes.show', compact('episode'));
    }
}
