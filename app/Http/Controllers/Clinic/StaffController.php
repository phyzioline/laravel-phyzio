<?php

namespace App\Http\Controllers\Clinic;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class StaffController extends BaseClinicController
{
    public function index()
    {
        $clinic = $this->getUserClinic();
        
        // Show empty state instead of redirecting
        if (!$clinic) {
            $staff = collect();
            return view('web.clinic.staff.index', compact('staff', 'clinic'));
        }

        // Get real staff members - filter by clinic's company if possible
        $query = User::whereIn('type', ['staff', 'receptionist', 'nurse', 'admin']);
        
        // Try to filter by company_id if column exists
        if (Schema::hasColumn('users', 'company_id')) {
            $query->where('company_id', $clinic->company_id);
        }
        
        $staff = $query->get()
            ->map(function($staffMember) {
                return (object)[
                    'id' => $staffMember->id,
                    'name' => $staffMember->name ?? ($staffMember->first_name . ' ' . $staffMember->last_name),
                    'role' => ucfirst($staffMember->type),
                    'email' => $staffMember->email,
                    'phone' => $staffMember->phone,
                    'status' => $staffMember->status ?? 'Active'
                ];
            });
        
        return view('web.clinic.staff.index', compact('staff', 'clinic'));
    }

    public function create()
    {
        $clinic = $this->getUserClinic();
        
        // Show form even if no clinic
        return view('web.clinic.staff.create', compact('clinic'));
    }

    public function store(Request $request)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return back()->with('error', 'Clinic not found.');
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'role' => 'required|in:staff,receptionist,nurse',
        ]);

        $staffData = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make('password'), // Default password - should be changed
            'type' => $request->role,
        ];
        
        // Link staff to clinic's company if company_id column exists
        if (Schema::hasColumn('users', 'company_id')) {
            $staffData['company_id'] = $clinic->company_id;
        }
        
        $staff = User::create($staffData);

        return redirect()->route('clinic.staff.index')
            ->with('success', 'Staff member registered successfully.');
    }
}
