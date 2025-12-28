<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Category;
use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::where('instructor_id', Auth::id())->withCount('enrollments')->latest()->get();
        return view('instructor.courses.index', compact('courses'));
    }

    public function show(Course $course)
    {
        if ($course->instructor_id !== Auth::id()) {
            abort(403);
        }
        // For now, redirect 'show' to the curriculum/edit view as it is the main dashboard for a course
        return redirect()->route('instructor.courses.edit', $course->id);
    }

    public function create()
    {
        $categories = Category::all();
        $skills = Skill::all(); // Load all skills for selection
        return view('instructor.courses.create', compact('categories', 'skills'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'specialty' => 'required|string',
            'level' => 'required|in:student,junior,senior,consultant',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'thumbnail' => 'nullable|image',
            'category_id' => 'required|exists:categories,id',
            'equipment_required' => 'nullable|array',
            'skills' => 'nullable|array',
            'skills.*' => 'exists:skills,id',
            'type' => 'nullable|in:online,offline',
            'seats' => 'nullable|integer',
            'trailer_url' => 'nullable|url',
        ]);

        DB::beginTransaction();
        try {
            $course = Course::create([
                'instructor_id' => Auth::id(),
                'title' => $validated['title'],
                'specialty' => $validated['specialty'],
                'level' => $validated['level'],
                'description' => $validated['description'],
                'price' => $validated['price'],
                'category_id' => $validated['category_id'],
                'equipment_required' => $validated['equipment_required'] ?? [],
                'status' => 'draft', // Will be changed to 'review' when instructor submits for approval
                'clinical_focus' => $request->clinical_focus,
                'seats' => $validated['seats'] ?? null,
                'type' => $validated['type'] ?? 'online',
                'trailer_url' => $validated['trailer_url'] ?? null,
            ]);

            if ($request->has('skills')) {
                $course->skills()->attach($validated['skills']);
            }
            
            // Handle Thumbnail Upload if present
            if ($request->hasFile('thumbnail')) {
                // $path = $request->file('thumbnail')->store('courses', 'public');
                // $course->update(['thumbnail' => $path]);
            }

            DB::commit();
            
            // Redirect to Curriculum Builder (Step 2)
            return redirect()->route('instructor.courses.edit', ['course' => $course->id, 'step' => 'curriculum'])
                             ->with('success', 'Course draft created. Now add modules and units.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to create course: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Course $course, Request $request)
    {
        if ($course->instructor_id !== Auth::id()) {
            abort(403);
        }

        $step = $request->query('step', 'basics');
        $categories = Category::all();
        $skills = Skill::all();

        if ($step === 'curriculum') {
             $course->load('modules.units');
             return view('instructor.courses.curriculum', compact('course'));
        }

        return view('instructor.courses.edit', compact('course', 'categories', 'skills'));
    }

    public function update(Request $request, Course $course)
    {
         if ($course->instructor_id !== Auth::id()) {
            abort(403);
        }

        // Handle status changes - when publishing, set to 'review' for admin approval
        if ($request->has('status')) {
            if ($request->status === 'published') {
                // Instructor wants to publish, but needs admin approval first
                $course->update(['status' => 'review']);
                return back()->with('success', 'Course submitted for review. It will be published after admin approval.');
            } else {
                $course->update(['status' => $request->status]);
            }
        }

        // Handle simple updates for basics
        $course->update($request->only('title', 'description', 'price', 'specialty', 'level'));
        
        if ($request->has('skills')) {
            $course->skills()->sync($request->skills);
        }

        return back()->with('success', 'Course updated successfully.');
    }
    
    // Add Module
    public function storeModule(Request $request, Course $course)
    {
         $request->validate(['title' => 'required']);
         $course->modules()->create([
             'title' => $request->title,
             'order' => $course->modules()->count() + 1
         ]);
         return back()->with('success', 'Module added.');
    }

    public function storeUnit(Request $request, Course $course, \App\Models\CourseModule $module)
    {
        $request->validate(['title' => 'required', 'unit_type' => 'required']);
        $module->units()->create([
            'title' => $request->title,
            'unit_type' => $request->unit_type,
            'duration_minutes' => $request->duration_minutes ?? 10,
            'order' => $module->units()->count() + 1
        ]);
        return back()->with('success', 'Unit added to module.');
    }
    
    public function destroy(Course $course)
    {
        if ($course->instructor_id !== Auth::id()) {
            abort(403);
        }
        $course->delete();
        return redirect()->route('instructor.courses.index')->with('success', 'Course deleted.');
    }
}
