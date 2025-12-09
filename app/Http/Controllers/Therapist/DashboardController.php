<?php

namespace App\Http\Controllers\Therapist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Load therapist profile if it exists
        $user = Auth::user();
        $user->load('therapistProfile');
        
        return view('web.therapist.dashboard');
    }
}
