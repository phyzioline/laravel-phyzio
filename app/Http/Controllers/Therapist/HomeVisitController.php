<?php

namespace App\Http\Controllers\Therapist;

use App\Http\Controllers\Controller;
use App\Traits\ChecksModuleAccess;
use Illuminate\Http\Request;

use App\Models\HomeVisit;
use App\Services\HomeVisit\HomeVisitService;

class HomeVisitController extends Controller
{
    use ChecksModuleAccess;

    protected $visitService;

    public function __construct(HomeVisitService $visitService)
    {
        // No middleware needed here as it's likely handled by routes or parent
        $this->visitService = $visitService;
    }

    public function index()
    {
        // Check module access
        $accessCheck = $this->checkModuleAccess('home_visit');
        if ($accessCheck !== true) {
            return $accessCheck;
        }
        // Unified Home Visits (Merged Logic)
        $allVisits = HomeVisit::where('therapist_id', auth()->id())
            ->with(['patient'])
            ->orderBy('scheduled_at', 'asc')
            ->get();

        $activeVisit = $allVisits->whereIn('status', ['accepted', 'on_way', 'in_session'])->first();
        $upcoming = $allVisits->where('status', 'scheduled')->where('scheduled_at', '>=', now()->toDateTimeString());
        $past = $allVisits->where('scheduled_at', '<', now()->toDateTimeString());
        $cancelled = $allVisits->where('status', 'cancelled');
        $completed = $allVisits->where('status', 'completed');
                        
        $availableVisits = HomeVisit::where('status', 'requested')
                            ->orderBy('created_at', 'desc')
                            ->get();

        return view('web.therapist.home_visits', compact(
            'allVisits', 'upcoming', 'past', 'cancelled', 'completed',
            'activeVisit', 'availableVisits'
        ));
    }

    public function updateStatus(Request $request, $id)
    {
        $visit = HomeVisit::findOrFail($id);
        $status = $request->status;
        
        if ($status == 'on_way') {
            $this->visitService->startTrip($visit);
        } elseif ($status == 'in_session') {
            $this->visitService->arrive($visit);
        }

        return back()->with('success', 'Visit status updated.');
    }

    public function accept($id)
    {
        try {
            $visit = HomeVisit::findOrFail($id);
            $this->visitService->acceptVisit(auth()->user(), $visit);
            return back()->with('success', 'Visit Accepted! Navigate to patient.' );
        } catch(\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function complete(Request $request, $id)
    {
        $visit = HomeVisit::findOrFail($id);
        $data = $request->validate([
            'chief_complaint' => 'required',
            'treatment_performed' => 'required|array',
            'assessment_findings' => 'nullable|array'
        ]);

        $this->visitService->completeVisit($visit, $data);
        return redirect()->route('therapist.home_visits.index')->with('success', 'Visit Completed & Paid.');
    }

    public function show($id)
    {
        $visit = HomeVisit::with(['patient', 'clinicalNotes', 'package'])->findOrFail($id);
        
        return view('web.therapist.home_visits.show', compact('visit'));
    }
}
