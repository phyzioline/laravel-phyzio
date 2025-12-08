<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::query()->where('status', 'published')->with('instructor');

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Category Filter
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Level Filter
        if ($request->has('level') && $request->level != '') {
            $query->where('level', $request->level);
        }

        // Sorting
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_low':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_high':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->latest();
                    break;
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $courses = $query->paginate(12);
        
        // Featured Courses (Random 5 for now, or specific flag if available)
        $featuredCourses = Course::where('status', 'published')->inRandomOrder()->take(5)->get();

        return view('web.courses.index', compact('courses', 'featuredCourses'));
    }

    public function show($id)
    {
        $course = Course::with(['lessons', 'instructor', 'enrollments'])->findOrFail($id);
        
        // Check if user is enrolled
        $isEnrolled = false;
        if(auth()->check()) {
            $isEnrolled = $course->enrollments()->where('user_id', auth()->id())->exists();
        }

        return view('web.courses.show', compact('course', 'isEnrolled'));
    }
}
