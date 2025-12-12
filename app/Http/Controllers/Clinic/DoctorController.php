<?php

namespace App\Http\Controllers\Clinic;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index()
    {
        // Mock data for company-doctors.html
        $doctors = collect([
            (object)[
                'id' => 1,
                'name' => 'Dr. Sarah Johnson',
                'specialty' => 'Sports Physiotherapy',
                'patients' => 124,
                'status' => 'Available',
                'image' => 'doc1.jpg' // Placeholder
            ],
            (object)[
                'id' => 2,
                'name' => 'Dr. David Smith',
                'specialty' => 'Orthopedics',
                'patients' => 98,
                'status' => 'In Session',
                'image' => 'doc2.jpg'
            ],
            // Add more as needed
        ]);
        return view('web.clinic.doctors.index', compact('doctors'));
    }

    public function create()
    {
        return view('web.clinic.doctors.create');
    }

    public function show($id)
    {
        // Mock doctor details
        $doctor = (object)[
                'id' => $id,
                'name' => 'Dr. Sarah Johnson',
                'specialty' => 'Sports Physiotherapy',
                'email' => 'sarah.j@clinic.com',
                'phone' => '+123 456 7890',
                'bio' => 'Experienced sports physiotherapist with 10+ years specializing in athlete recovery.',
                'patients' => 124,
                'status' => 'Available',
                'image' => 'doc1.jpg' 
        ];
        return view('web.clinic.doctors.show', compact('doctor'));
    }
}
