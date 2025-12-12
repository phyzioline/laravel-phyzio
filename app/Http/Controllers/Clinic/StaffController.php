<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StaffController extends Controller
{
    public function index()
    {
        // Mock data for company-staff.html
        $staff = collect([
            (object)[
                'id' => 101,
                'name' => 'Alice Miller',
                'role' => 'Receptionist',
                'email' => 'alice@clinic.com',
                'phone' => '+1234567890',
                'status' => 'Active'
            ],
            (object)[
                'id' => 102,
                'name' => 'John Davis',
                'role' => 'Nurse',
                'email' => 'john@clinic.com',
                'phone' => '+1234567891',
                'status' => 'On Leave'
            ],
        ]);
        return view('web.clinic.staff.index', compact('staff'));
    }

    public function create()
    {
         return view('web.clinic.staff.create');
    }
}
