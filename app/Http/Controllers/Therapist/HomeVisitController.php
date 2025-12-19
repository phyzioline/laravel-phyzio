<?php

namespace App\Http\Controllers\Therapist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\HomeVisit;
use App\Services\HomeVisit\HomeVisitService;

class HomeVisitController extends Controller
{
    protected $visitService;

    public function __construct(HomeVisitService $visitService)
    {
        // No middleware needed here as it's likely handled by routes or parent
        $this->visitService = $visitService;
    }

    public function index()
    {
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
        // Logic to update visit status
        return redirect()->back()->with('success', 'Visit status updated.');
    }

    public function show($id)
    {
        $visit = HomeVisit::with(['patient', 'clinicalNotes', 'package'])->findOrFail($id);
        
        return view('web.therapist.home_visits.show', compact('visit'));
    }
}
