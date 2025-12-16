<?php

namespace App\Http\Controllers\Therapist;

use App\Http\Controllers\Controller;
use App\Models\HomeVisit;
use App\Services\HomeVisit\HomeVisitService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisitManagementController extends Controller
{
    protected $visitService;

    public function __construct(HomeVisitService $visitService)
    {
        $this->middleware('auth');
        $this->visitService = $visitService;
    }

    // Dashboard: List incoming requests and active visits
    public function index()
    {
        $activeVisit = HomeVisit::where('therapist_id', Auth::id())
                        ->whereIn('status', ['accepted', 'on_way', 'in_session'])
                        ->first();
                        
        $availableVisits = HomeVisit::where('status', 'requested')
                            ->orderBy('created_at', 'desc')
                            ->get(); // In real app, filter by location

        return view('therapist.visits.index', compact('activeVisit', 'availableVisits'));
    }

    public function accept(HomeVisit $visit)
    {
        try {
            $this->visitService->acceptVisit(Auth::user(), $visit);
            return back()->with('success', 'Visit Accepted! Navigate to patient.' );
        } catch(\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function updateStatus(Request $request, HomeVisit $visit)
    {
        $status = $request->status;
        
        if ($status == 'on_way') {
            $this->visitService->startTrip($visit);
        } elseif ($status == 'in_session') {
            $this->visitService->arrive($visit);
        }

        return back();
    }

    public function complete(Request $request, HomeVisit $visit)
    {
        $data = $request->validate([
            'chief_complaint' => 'required',
            'treatment_performed' => 'required|array',
            'assessment_findings' => 'nullable|array'
        ]);

        $this->visitService->completeVisit($visit, $data);
        return redirect()->route('therapist.visits.index')->with('success', 'Visit Completed & Paid.');
    }
}
