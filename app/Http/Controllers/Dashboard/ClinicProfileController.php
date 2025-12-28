<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ClinicProfileController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:clinic_profiles-index', only: ['index']),
            new Middleware('can:clinic_profiles-create', only: ['create', 'store']),
            new Middleware('can:clinic_profiles-show', only: ['show']),
            new Middleware('can:clinic_profiles-update', only: ['edit', 'update']),
            new Middleware('can:clinic_profiles-delete', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Filter by status if provided
        $query = \App\Models\Clinic::with('company')
            ->where('is_deleted', false);
        
        // Filter by approval status
        if ($request->has('status') && $request->status === 'pending') {
            $query->where('is_active', false);
        } elseif ($request->has('status') && $request->status === 'approved') {
            $query->where('is_active', true);
        }
        
        $clinic_profiles = $query->orderBy('created_at', 'desc')
            ->get()
            ->map(function($clinic) {
                return (object)[
                    'id' => $clinic->id,
                    'name' => $clinic->name,
                    'plan' => ucfirst($clinic->subscription_tier ?? 'basic'),
                    'status' => $clinic->is_active ? 'active' : 'pending',
                    'email' => $clinic->email,
                    'phone' => $clinic->phone,
                    'city' => $clinic->city,
                    'country' => $clinic->country,
                    'created_at' => $clinic->created_at,
                    'company_name' => $clinic->company->name ?? 'N/A',
                    'company_id' => $clinic->company_id,
                ];
            });
        
        return view('dashboard.clinic_profiles.index', compact('clinic_profiles'));
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
        //
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
        $clinic = \App\Models\Clinic::findOrFail($id);
        
        $request->validate([
            'action' => 'required|in:approve,reject,activate,deactivate',
        ]);
        
        if ($request->action === 'approve' || $request->action === 'activate') {
            $clinic->update([
                'is_active' => true,
            ]);
            
            return redirect()->route('dashboard.clinic_profiles.index')
                ->with('success', 'Clinic approved and activated successfully.');
        } elseif ($request->action === 'reject' || $request->action === 'deactivate') {
            $clinic->update([
                'is_active' => false,
            ]);
            
            return redirect()->route('dashboard.clinic_profiles.index')
                ->with('success', 'Clinic deactivated successfully.');
        }
        
        return redirect()->back()->with('error', 'Invalid action.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
