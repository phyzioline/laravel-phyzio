<?php

namespace App\Http\Controllers\Therapist;

use App\Http\Controllers\Controller;
use App\Traits\ChecksModuleAccess;
use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{
    use ChecksModuleAccess;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Check module access
        $accessCheck = $this->checkModuleAccess('courses');
        if ($accessCheck !== true) {
            return $accessCheck;
        }
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
            'seats' => 'nullable|integer|min:0', // Added seats validation
            'level' => 'required|in:beginner,intermediate,advanced',
            'type' => 'required|in:online,offline',
            'status' => 'required|in:draft,published',
            'video_url' => 'nullable|url|required_if:type,online',
        ]);

        $data = $request->all();
        $data['instructor_id'] = Auth::id();
        
        // If therapist tries to publish, set to 'review' for admin approval
        if ($request->input('status') === 'published') {
            $data['status'] = 'review';
        } else {
            $data['status'] = $request->input('status');
        } 

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('courses/thumbnails', 'public');
        }

        if ($request->hasFile('video_file')) {
            $data['video_file'] = $request->file('video_file')->store('courses/videos', 'public');
            $data['type'] = 'online'; // Force type if video uploaded
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
            'seats' => 'nullable|integer|min:0', // Added seats validation
            'level' => 'required|in:beginner,intermediate,advanced',
            'type' => 'required|in:online,offline',
            'status' => 'required|in:draft,published',
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
