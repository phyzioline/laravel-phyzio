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

        // CRITICAL: Only show staff members assigned to THIS clinic via clinic_staff table
        // This ensures proper data isolation between clinics
        // Note: clinic_staff.role ENUM values are: ['therapist', 'admin', 'receptionist', 'doctor']
        // We map form values: 'staff'->'admin', 'nurse'->'admin', 'receptionist'->'receptionist'
        $staffIds = \App\Models\ClinicStaff::where('clinic_id', $clinic->id)
            ->whereIn('role', ['admin', 'receptionist']) // Only valid ENUM values for staff
            ->where('is_active', true)
            ->pluck('user_id')
            ->toArray();
        
        if (empty($staffIds)) {
            // No staff assigned to this clinic - return empty collection
            $staff = collect();
        } else {
            $staff = User::whereIn('id', $staffIds)
                ->whereIn('type', ['staff', 'receptionist', 'nurse', 'admin'])
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
        }
        
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

        $validator = \Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'role' => 'required|in:staff,receptionist,nurse',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $staffData = [
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make('password'), // Default password - should be changed
            'type' => $request->role,
        ];
        
        // Only set first_name and last_name if columns exist
        if (Schema::hasColumn('users', 'first_name')) {
            $staffData['first_name'] = $request->first_name;
        }
        if (Schema::hasColumn('users', 'last_name')) {
            $staffData['last_name'] = $request->last_name;
        }
        
        // Link staff to clinic's company if company_id column exists
        if (Schema::hasColumn('users', 'company_id')) {
            $staffData['company_id'] = $clinic->company_id;
        }
        
        $staff = User::create($staffData);
        
        // CRITICAL: Assign staff to this clinic via clinic_staff table
        // Map form role values to clinic_staff ENUM values
        $roleMapping = [
            'staff' => 'admin',        // Map 'staff' to 'admin' in clinic_staff
            'receptionist' => 'receptionist',
            'nurse' => 'admin',        // Map 'nurse' to 'admin' in clinic_staff
        ];
        
        $clinicStaffRole = $roleMapping[$request->role] ?? 'admin';
        
        \App\Models\ClinicStaff::create([
            'clinic_id' => $clinic->id,
            'user_id' => $staff->id,
            'role' => $clinicStaffRole,
            'is_active' => true,
            'hired_date' => now(),
        ]);

        return redirect()->route('clinic.staff.index')
            ->with('success', 'Staff member registered successfully.');
    }

    public function edit($id)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return back()->with('error', 'Clinic not found.');
        }
        
        // CRITICAL: Verify staff member belongs to this clinic
        // Note: clinic_staff.role ENUM values are: ['therapist', 'admin', 'receptionist', 'doctor']
        $clinicStaff = \App\Models\ClinicStaff::where('clinic_id', $clinic->id)
            ->where('user_id', $id)
            ->whereIn('role', ['admin', 'receptionist']) // Only valid ENUM values for staff
            ->first();
        
        if (!$clinicStaff) {
            abort(403, 'This staff member is not assigned to your clinic.');
        }
        
        $staffMember = User::findOrFail($id);
        
        return view('web.clinic.staff.edit', compact('staffMember', 'clinic'));
    }

    public function update(Request $request, $id)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return back()->with('error', 'Clinic not found.');
        }

        // CRITICAL: Verify staff member belongs to this clinic
        // Note: clinic_staff.role ENUM values are: ['therapist', 'admin', 'receptionist', 'doctor']
        $clinicStaff = \App\Models\ClinicStaff::where('clinic_id', $clinic->id)
            ->where('user_id', $id)
            ->whereIn('role', ['admin', 'receptionist']) // Only valid ENUM values for staff
            ->first();
        
        if (!$clinicStaff) {
            abort(403, 'This staff member is not assigned to your clinic.');
        }
        
        $staffMember = User::findOrFail($id);

        $validator = \Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|string|max:20',
            'role' => 'required|in:staff,receptionist,nurse',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $updateData = [
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'type' => $request->role,
        ];
        
        // Only set first_name and last_name if columns exist
        if (Schema::hasColumn('users', 'first_name')) {
            $updateData['first_name'] = $request->first_name;
        }
        if (Schema::hasColumn('users', 'last_name')) {
            $updateData['last_name'] = $request->last_name;
        }
        
        $staffMember->update($updateData);
        
        // Update role in clinic_staff if it changed
        // Map form role values to clinic_staff ENUM values
        $roleMapping = [
            'staff' => 'admin',        // Map 'staff' to 'admin' in clinic_staff
            'receptionist' => 'receptionist',
            'nurse' => 'admin',        // Map 'nurse' to 'admin' in clinic_staff
        ];
        
        $clinicStaffRole = $roleMapping[$request->role] ?? 'admin';
        
        if ($clinicStaff->role !== $clinicStaffRole) {
            $clinicStaff->update(['role' => $clinicStaffRole]);
        }

        return redirect()->route('clinic.staff.index')
            ->with('success', 'Staff member updated successfully.');
    }

    public function destroy($id)
    {
        $clinic = $this->getUserClinic();
        
        if (!$clinic) {
            return back()->with('error', 'Clinic not found.');
        }

        // CRITICAL: Verify staff member belongs to this clinic
        // Note: clinic_staff.role ENUM values are: ['therapist', 'admin', 'receptionist', 'doctor']
        $clinicStaff = \App\Models\ClinicStaff::where('clinic_id', $clinic->id)
            ->where('user_id', $id)
            ->whereIn('role', ['admin', 'receptionist']) // Only valid ENUM values for staff
            ->first();
        
        if (!$clinicStaff) {
            abort(403, 'This staff member is not assigned to your clinic.');
        }
        
        $staffMember = User::findOrFail($id);
        
        // Remove from clinic_staff table (soft delete relationship)
        $clinicStaff->update([
            'is_active' => false,
            'terminated_date' => now(),
        ]);
        
        // Optionally delete the user account (uncomment if needed)
        // $staffMember->delete();

        return redirect()->route('clinic.staff.index')
            ->with('success', 'Staff member deleted successfully.');
    }
}
