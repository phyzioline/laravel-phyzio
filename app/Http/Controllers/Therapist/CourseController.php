<?php

namespace App\Http\Controllers\Therapist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Only show courses where the logged-in therapist is the instructor
        $courses = Course::where('instructor_id', Auth::id())->with('instructor')->get();
        return view('therapist.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('therapist.courses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'level' => 'required|in:beginner,intermediate,advanced',
            'type' => 'required|in:online,offline',
            'video_url' => 'nullable|url|required_if:type,online',
        ]);

        $data = $request->all();
        $data['instructor_id'] = Auth::id(); // Force instructor to be current user
        $data['status'] = 'draft'; 

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('courses/thumbnails', 'public');
        }

        Course::create($data);

        return redirect()->route('therapist.courses.index')->with('message', ['type' => 'success', 'text' => 'Course created successfully!']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $course = Course::where('id', $id)->where('instructor_id', Auth::id())->firstOrFail();
        return view('therapist.courses.edit', compact('course'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $course = Course::where('id', $id)->where('instructor_id', Auth::id())->firstOrFail();

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'level' => 'required|in:beginner,intermediate,advanced',
            'type' => 'required|in:online,offline',
            'video_url' => 'nullable|url|required_if:type,online',
        ]);

        $data = $request->all();

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('courses/thumbnails', 'public');
        }

        $course->update($data);

        return redirect()->route('therapist.courses.index')->with('message', ['type' => 'success', 'text' => 'Course updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $course = Course::where('id', $id)->where('instructor_id', Auth::id())->firstOrFail();
        $course->delete();

        return redirect()->route('therapist.courses.index')->with('message', ['type' => 'success', 'text' => 'Course deleted successfully!']);
    }
}
