<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TherapistProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $profiles = \App\Models\TherapistProfile::with('user')->get();
        return view('dashboard.therapist_profiles.index', compact('profiles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $profile = \App\Models\TherapistProfile::with('user')->findOrFail($id);
        return view('dashboard.therapist_profiles.show', compact('profile'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $profile = \App\Models\TherapistProfile::findOrFail($id);
        
        // Handle status updates
        if ($request->has('status')) {
            $profile->update(['status' => $request->status]);
            return redirect()->back()->with('success', 'Therapist status updated successfully.');
        }
        
        // Handle module permission updates
        if ($request->has('field') && $request->has('value')) {
            $field = $request->field;
            $value = $request->value;
            
            // Validate field
            if (in_array($field, ['can_access_clinic', 'can_access_instructor'])) {
                $profile->update([$field => (bool)$value]);
                return redirect()->back()->with('success', 'Permission updated successfully.');
            }
        }
        
        return redirect()->back()->with('error', 'Invalid request.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
