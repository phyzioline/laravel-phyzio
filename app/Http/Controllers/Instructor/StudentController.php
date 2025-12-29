<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Get instructor's courses
        $instructorCourses = Course::where('instructor_id', $user->id)->pluck('id');
        
        // Get all enrollments for instructor's courses with student and course info
        $query = Enrollment::whereIn('course_id', $instructorCourses)
            ->with(['student', 'course'])
            ->orderBy('enrolled_at', 'desc');
        
        // Filter by course if provided
        if ($request->has('course_id') && $request->course_id) {
            $query->where('course_id', $request->course_id);
        }
        
        // Filter by status if provided
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }
        
        // Search by student name or email
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->whereHas('student', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        $enrollments = $query->paginate(20);
        
        // Get instructor's courses for filter dropdown
        $courses = Course::where('instructor_id', $user->id)
            ->withCount('enrollments')
            ->orderBy('title')
            ->get();
        
        // Statistics
        $totalStudents = Enrollment::whereIn('course_id', $instructorCourses)
            ->distinct('user_id')
            ->count('user_id');
        
        $activeEnrollments = Enrollment::whereIn('course_id', $instructorCourses)
            ->where('status', 'active')
            ->count();
        
        $completedEnrollments = Enrollment::whereIn('course_id', $instructorCourses)
            ->where('status', 'completed')
            ->count();
        
        return view('web.instructor.students.index', compact(
            'enrollments',
            'courses',
            'totalStudents',
            'activeEnrollments',
            'completedEnrollments'
        ));
    }
    
    public function show($studentId)
    {
        $user = Auth::user();
        
        // Get instructor's courses
        $instructorCourses = Course::where('instructor_id', $user->id)->pluck('id');
        
        // Get student info
        $student = \App\Models\User::findOrFail($studentId);
        
        // Get all enrollments for this student in instructor's courses
        $enrollments = Enrollment::whereIn('course_id', $instructorCourses)
            ->where('user_id', $studentId)
            ->with('course')
            ->orderBy('enrolled_at', 'desc')
            ->get();
        
        // Calculate student statistics
        $totalCourses = $enrollments->count();
        $completedCourses = $enrollments->where('status', 'completed')->count();
        $totalSpent = $enrollments->sum('paid_amount');
        
        return view('web.instructor.students.show', compact(
            'student',
            'enrollments',
            'totalCourses',
            'completedCourses',
            'totalSpent'
        ));
    }
}

