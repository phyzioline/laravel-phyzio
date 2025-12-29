<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Exercise;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    public function __construct()
    {
        // Check admin access in constructor
        if (!auth()->check() || (!auth()->user()->hasRole('admin') && !auth()->user()->hasRole('super-admin'))) {
            abort(403, 'Unauthorized. This section is restricted to administrators only.');
        }
    }

    public function index()
    {
        $exercises = Exercise::latest()->paginate(10);
        return view('dashboard.exercises.index', compact('exercises'));
    }

    public function create()
    {
        return view('dashboard.exercises.create');
    }

    public function store(Request $request)
    {
        // Validation and storage logic
        return redirect()->route('dashboard.exercises.index');
    }

    // Add show, edit, update, destroy methods as needed
}
