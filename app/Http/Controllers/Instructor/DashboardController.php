<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function index()
    {
        $courses = Course::where('instructor_id', Auth::id())
            ->withCount('enrollments')
            ->latest()
            ->get();
        
        return view('web.instructor.dashboard', compact('courses'));
    }

    public function create()
    {
        return view('web.instructor.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'thumbnail' => 'nullable|image'
        ]);

        $validated['instructor_id'] = Auth::id();
        $validated['status'] = 'draft';

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('courses', 'public');
        }

        Course::create($validated);

        return redirect()->route('instructor.dashboard')->with('success', 'Course created successfully!');
    }
}
