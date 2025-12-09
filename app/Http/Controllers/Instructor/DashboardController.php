<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Stats
        $totalCourses = Course::where('instructor_id', $user->id)->count();
        $activeStudents = 1247; // Placeholder: Need Enrollment Logic
        $totalRevenue = 125480; // Placeholder: Need Transaction Logic
        $pendingReviews = 3; // Placeholder

        // Recent Activities (Mock)
        $activities = [
            ['title' => 'New Student Enrolled', 'desc' => 'Sarah Johnson joined "Advanced Cardiology"', 'time' => '2 hours ago'],
            ['title' => 'Course Approved', 'desc' => 'Your course "Pediatric Basics" is now live', 'time' => '4 hours ago'],
            ['title' => 'Review Received', 'desc' => '5 star review from Ahmed Ali', 'time' => '1 day ago'],
        ];

        return view('web.instructor.dashboard', compact('totalCourses', 'activeStudents', 'totalRevenue', 'pendingReviews', 'activities'));
    }

    public function create()
    {
        return view('web.instructor.courses.create');
    }

    public function store(Request $request)
    {
        // Validation and creation logic will go here
    }
}
