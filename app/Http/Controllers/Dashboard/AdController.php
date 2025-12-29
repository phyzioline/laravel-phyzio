<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('super-admin')) {
                abort(403, 'Unauthorized. This section is restricted to administrators only.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $ads = Ad::latest()->paginate(10);
        return view('dashboard.ads.index', compact('ads'));
    }

    public function settings()
    {
        // Fetch accounts, or create stub data if empty
        $facebookAccounts = \App\Models\AdAccount::where('platform', 'facebook')->get();
        $googleAccounts = \App\Models\AdAccount::where('platform', 'google')->get();

        // If no accounts, we can pass empty collections or mock data for the visual preview if desired by the user.
        // For now, let's pass what we have.
        
        return view('dashboard.ads.settings', compact('facebookAccounts', 'googleAccounts'));
    }

    public function toggleTracking(Request $request, $id)
    {
        $account = \App\Models\AdAccount::findOrFail($id);
        $account->auto_tracking = $request->input('auto_tracking');
        $account->save();
        
        return response()->json(['success' => true]);
    }

    public function create()
    {
        return view('dashboard.ads.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'link' => 'required|url',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('ads', 'public');
            $data['image'] = $path;
        }

        Ad::create($data);

        return redirect()->route('dashboard.ads.index')->with('success', 'Ad created successfully.');
    }

    public function edit(Ad $ad)
    {
        return view('dashboard.ads.edit', compact('ad'));
    }

    public function update(Request $request, Ad $ad)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'link' => 'required|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            // Delete old image
            if ($ad->image) {
                Storage::disk('public')->delete($ad->image);
            }
            $path = $request->file('image')->store('ads', 'public');
            $data['image'] = $path;
        }

        $ad->update($data);

        return redirect()->route('dashboard.ads.index')->with('success', 'Ad updated successfully.');
    }

    public function destroy(Ad $ad)
    {
        if ($ad->image) {
            Storage::disk('public')->delete($ad->image);
        }
        $ad->delete();

        return redirect()->route('dashboard.ads.index')->with('success', 'Ad deleted successfully.');
    }
}
