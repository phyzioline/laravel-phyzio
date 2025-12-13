<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = \App\Models\Course::with('instructor')->get();
        return view('dashboard.courses.index', compact('courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.courses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'category_id' => 'nullable|exists:categories,id',
            'level' => 'required|in:beginner,intermediate,advanced',
            'status' => 'required|in:draft,pending,published',
            'type' => 'required|in:online,offline',
            'video_url' => 'nullable|url|required_if:type,online',
        ]);

        \App\Models\Course::create($request->all());
        return redirect()->route('dashboard.courses.index')->with('message', ['type' => 'success', 'text' => 'Course created successfully!']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $course = \App\Models\Course::findOrFail($id);
        return view('dashboard.courses.edit', compact('course'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $course = \App\Models\Course::findOrFail($id);
        
        $request->validate([
            'title' => 'sometimes|string|max:255',
            'status' => 'sometimes|in:draft,pending,published',
        ]);

        $course->update($request->all());
        return redirect()->route('dashboard.courses.index')->with('message', ['type' => 'success', 'text' => 'Course updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
