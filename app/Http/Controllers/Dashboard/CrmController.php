<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class CrmController extends Controller
{
    public function index()
    {
        $patients = Patient::with('clinic')->latest()->paginate(10);
        return view('dashboard.crm.index', compact('patients'));
    }

    public function show(Patient $patient)
    {
        return view('dashboard.crm.show', compact('patient'));
    }
    
    // Additional CRM features like notes or history can be added here
}
