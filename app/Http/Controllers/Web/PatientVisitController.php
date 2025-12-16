<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\HomeVisit\HomeVisitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientVisitController extends Controller
{
    protected $visitService;

    public function __construct(HomeVisitService $visitService)
    {
        $this->middleware('auth');
        $this->visitService = $visitService;
    }

    public function create()
    {
        return view('web.visits.patient.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'address' => 'required|string',
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'complain_type' => 'required|string',
            'scheduled_at' => 'nullable|date',
        ]);

        try {
            $visit = $this->visitService->requestVisit(Auth::user(), $data);
            return redirect()->route('patient.visits.show', $visit->id)->with('success', 'Therapists are being notified!');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show($id)
    {
        $visit = Auth::user()->homeVisits()->findOrFail($id);
        return view('web.visits.patient.show', compact('visit'));
    }
}
