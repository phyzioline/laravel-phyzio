<?php

namespace App\Http\Controllers\Clinic;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class StaffController extends BaseClinicController
{
    public function index()
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return redirect()->route('clinic.dashboard')
                ->with('error', 'Clinic not found.');
        }

        // Get real staff members (users with type 'staff' or 'receptionist', etc.)
        $staff = User::whereIn('type', ['staff', 'receptionist', 'nurse', 'admin'])
            ->get()
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
        
        if (!$clinic) {
            return redirect()->route('clinic.dashboard')
                ->with('error', 'Clinic not found.');
        }

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

        $staff = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make('password'), // Default password
            'type' => $request->role,
        ]);

        return redirect()->route('clinic.staff.index')
            ->with('success', 'Staff member registered successfully.');
    }
}
