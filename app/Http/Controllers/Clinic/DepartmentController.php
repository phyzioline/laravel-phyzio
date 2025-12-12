<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        // Mock data for company-departments.html (Context adjusted to Physical Therapy)
        $departments = collect([
            (object)[
                'name' => 'Sports Rehabilitation',
                'head' => 'Dr. Sarah Johnson',
                'doctors_count' => 4,
                'status' => 'Active',
                'description' => 'Injury recovery and performance enhancement.'
            ],
            (object)[
                'name' => 'Orthopedics',
                'head' => 'Dr. David Smith',
                'doctors_count' => 6,
                'status' => 'Busy',
                'description' => 'Musculoskeletal diagnostics and treatment.'
            ],
            (object)[
                'name' => 'Pediatrics',
                'head' => 'Dr. Emily Wilson',
                'doctors_count' => 3,
                'status' => 'Active',
                'description' => 'Developmental therapy for children.'
            ]
        ]);
        return view('web.clinic.departments.index', compact('departments'));
    }

    public function create()
    {
         return view('web.clinic.departments.create');
    }
}
