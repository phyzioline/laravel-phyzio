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
        if ($request->has('search') && $request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('instructor', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Category Filter
        if ($request->has('category') && $request->filled('category')) {
            $query->where('category_id', $request->category); // Ensure category_id exists in migration or use generic field if mocking
        }

        // Level Filter
        if ($request->has('level') && $request->filled('level')) {
            $query->where('level', $request->level);
        }
        
        // Language Filter
        if ($request->has('language') && $request->filled('language')) {
            $query->where('language', $request->language);
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
                case 'rating':
                    // Assuming aggregation or relation for ratings
                    $query->withCount('reviews')->orderBy('reviews_count', 'desc'); 
                    break;
                case 'newest':
                default:
                    $query->latest();
                    break;
            }
        } else {
            $query->latest();
        }

        $courses = $query->paginate(12)->withQueryString();
        
        // Featured Courses 
        $featuredCourses = Course::where('status', 'published')
                                 ->whereNotNull('thumbnail')
                                 ->inRandomOrder()
                                 ->take(5)
                                 ->get();
                                 
        // Categories (Mock or Fetch if model exists)
        $categories = collect([
            (object)['id' => 1, 'name' => 'Orthopedics'],
            (object)['id' => 2, 'name' => 'Neurology'],
            (object)['id' => 3, 'name' => 'Pediatrics'],
            (object)['id' => 4, 'name' => 'Manual Therapy'],
            (object)['id' => 5, 'name' => 'Sports Medicine'],
        ]);

        return view('web.courses.index', compact('courses', 'featuredCourses', 'categories'));
    }

    public function show($id)
    {
        $course = Course::with(['modules.units', 'instructor', 'enrollments', 'skills', 'clinicalCases'])->findOrFail($id);
        
        // Check if user is enrolled
        $isEnrolled = false;
        if(auth()->check()) {
            $isEnrolled = $course->enrollments()->where('user_id', auth()->id())->exists();
        }

        return view('web.courses.show', compact('course', 'isEnrolled'));
    }
}
