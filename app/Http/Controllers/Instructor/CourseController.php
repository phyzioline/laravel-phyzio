<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Category; // Assuming Category model exists
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CourseController extends Controller
{
    /**
     * Show Step 1: Basic Information
     */
    public function create()
    {
        // $categories = Category::all(); // Uncomment when Category model is ready
        $categories = collect([
            (object)['id' => 1, 'name' => 'Orthopedics'],
            (object)['id' => 2, 'name' => 'Neurology'],
            (object)['id' => 3, 'name' => 'Pediatrics'],
            (object)['id' => 4, 'name' => 'Manual Therapy'],
            (object)['id' => 5, 'name' => 'Sports Medicine'],
        ]);
        
        return view('web.instructor.courses.create_step1', compact('categories'));
    }

    /**
     * Store Step 1 and redirect to Step 2
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category_id' => 'required', // |exists:categories,id
            'level' => 'required|string',
            'description' => 'required|string',
            'thumbnail' => 'nullable|image|max:2048',
        ]);

        $course = new Course();
        $course->instructor_id = Auth::id();
        $course->title = $request->title;
        // $course->category_id = $request->category_id; // Add to migration if missed
        $course->level = $request->level;
        $course->description = $request->description;
        $course->language = $request->language ?? 'English';
        $course->status = 'draft';

        if ($request->hasFile('thumbnail')) {
            $path = $request->file('thumbnail')->store('courses/thumbnails', 'public');
            $course->thumbnail = $path;
        }

        $course->save();

        return redirect()->route('instructor.courses.edit', ['course' => $course->id, 'step' => 2]);
    }

    /**
     * Show Edit Form for Steps 1, 2, 3, 4
     */
    public function edit($courseId, Request $request)
    {
        $course = Course::where('instructor_id', Auth::id())->findOrFail($courseId);
        $step = $request->query('step', 1);

        if ($step == 2) {
            $course->load('lessons');
            return view('web.instructor.courses.create_step2', compact('course'));
        }
        
        if ($step == 3) {
            return view('web.instructor.courses.create_step3', compact('course'));
        }

        if ($step == 4) {
            return view('web.instructor.courses.create_step4', compact('course'));
        }

        // Default back to step 1
        $categories = collect([
            (object)['id' => 1, 'name' => 'Orthopedics'],
            (object)['id' => 2, 'name' => 'Neurology'],
            (object)['id' => 3, 'name' => 'Pediatrics'],
            (object)['id' => 4, 'name' => 'Manual Therapy'],
            (object)['id' => 5, 'name' => 'Sports Medicine'],
        ]);
        return view('web.instructor.courses.create_step1', compact('course', 'categories'));
    }

    public function update(Request $request, $courseId)
    {
        $course = Course::where('instructor_id', Auth::id())->findOrFail($courseId);
        $step = $request->query('step', 1);

        if ($step == 1) {
            // Update Basic Info
             $request->validate([
                'title' => 'required|string|max:255',
            ]);
            $course->update($request->only(['title', 'level', 'description', 'language']));
             if ($request->hasFile('thumbnail')) {
                $path = $request->file('thumbnail')->store('courses/thumbnails', 'public');
                $course->thumbnail = $path;
                $course->save();
            }
            return redirect()->route('instructor.courses.edit', ['course' => $course->id, 'step' => 2]);
        }

        if ($step == 3) {
            // Update Pricing
             $request->validate([
                'price' => 'numeric|min:0',
            ]);
            $course->update($request->only(['price', 'discount_price', 'refund_policy']));
            return redirect()->route('instructor.courses.edit', ['course' => $course->id, 'step' => 4]);
        }
        
        if ($step == 4) {
            // Submit for Review
            $course->status = 'review';
            $course->save();
            return redirect()->route('instructor.dashboard')->with('success', 'Course submitted for review!');
        }

        return back();
    }
}
