<?php

namespace App\Http\Controllers\Therapist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Load therapist profile if it exists
        $user = Auth::user();
        $user->load('therapistProfile');
        
        return view('web.therapist.dashboard');
    }
    
    public function profile()
    {
        $user = Auth::user();
        $user->load('therapistProfile');
        
        return view('web.therapist.profile');
    }
}
