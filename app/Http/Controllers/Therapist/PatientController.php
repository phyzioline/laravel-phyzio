<?php

namespace App\Http\Controllers\Therapist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index()
    {
        // Placeholder data - replace with actual model calls later
        $patients = collect([
            (object)[
                'id' => '#P001234',
                'name' => 'John Doe',
                'age' => 45,
                'gender' => 'Male',
                'last_visit' => 'Dec 20, 2024',
                'status' => 'Needs Follow-up',
                'conditions' => ['Hypertension', 'Diabetes'],
                'image_initial' => 'J'
            ],
            (object)[
                'id' => '#P001235',
                'name' => 'Sarah Miller',
                'age' => 38,
                'gender' => 'Female',
                'last_visit' => 'Dec 18, 2024',
                'status' => 'Stable',
                'conditions' => ['Hypertension', 'Previous MI'],
                'image_initial' => 'S'
            ],
            // Add more mock data as needed to match screenshot
        ]);

        return view('web.therapist.patients.index', compact('patients'));
    }

    public function create()
    {
        return view('web.therapist.patients.create');
    }

    public function show($id)
    {
        // Mock patient details
        $patient = (object)[
            'id' => $id,
            'name' => 'John Doe',
            'email' => 'john.doe@example.com',
            'phone' => '+1 234 567 8900',
            'age' => 45,
            'gender' => 'Male',
            'condition' => 'ACL Tear Recovery',
            'status' => 'Active',
            'last_visit' => '2024-12-10',
            'next_visit' => '2024-12-15',
            'image' => 'https://ui-avatars.com/api/?name=John+Doe&background=0D8ABC&color=fff'
        ];
        return view('web.therapist.patients.show', compact('patient'));
    }
}
