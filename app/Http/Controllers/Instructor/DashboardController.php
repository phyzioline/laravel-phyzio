<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get instructor's courses
        $instructorCourses = Course::where('instructor_id', $user->id)->pluck('id');
        
        // Real Stats
        $totalCourses = $instructorCourses->count();
        
        // Total unique students enrolled in instructor's courses
        $activeStudents = Enrollment::whereIn('course_id', $instructorCourses)
            ->distinct('user_id')
            ->count('user_id');
        
        // Monthly revenue (current month)
        $totalRevenue = Enrollment::whereIn('course_id', $instructorCourses)
            ->whereMonth('enrolled_at', Carbon::now()->month)
            ->whereYear('enrolled_at', Carbon::now()->year)
            ->sum('paid_amount') ?? 0;
        
        // New enrollments this month
        $newEnrollments = Enrollment::whereIn('course_id', $instructorCourses)
            ->whereMonth('enrolled_at', Carbon::now()->month)
            ->whereYear('enrolled_at', Carbon::now()->year)
            ->count();
        
        // Pending enrollments (enrollments that are not completed or cancelled)
        $pendingEnrollments = Enrollment::whereIn('course_id', $instructorCourses)
            ->whereNotIn('status', ['completed', 'cancelled', 'refunded'])
            ->count();
        
        // Last month's stats for comparison
        $lastMonthRevenue = Enrollment::whereIn('course_id', $instructorCourses)
            ->whereMonth('enrolled_at', Carbon::now()->subMonth()->month)
            ->whereYear('enrolled_at', Carbon::now()->subMonth()->year)
            ->sum('paid_amount') ?? 0;
        
        $lastMonthStudents = Enrollment::whereIn('course_id', $instructorCourses)
            ->whereMonth('enrolled_at', Carbon::now()->subMonth()->month)
            ->whereYear('enrolled_at', Carbon::now()->subMonth()->year)
            ->distinct('user_id')
            ->count('user_id');
        
        // Calculate percentage changes
        $revenueChange = $lastMonthRevenue > 0 
            ? round((($totalRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100, 1)
            : 0;
        
        $studentsChange = $lastMonthStudents > 0
            ? round((($activeStudents - $lastMonthStudents) / $lastMonthStudents) * 100, 1)
            : 0;
        
        // Courses created this month
        $newCoursesThisMonth = Course::where('instructor_id', $user->id)
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();
        
        // Top performing courses (by enrollment count)
        $topCourses = Course::where('instructor_id', $user->id)
            ->withCount('enrollments')
            ->orderBy('enrollments_count', 'desc')
            ->limit(5)
            ->get();
        
        // Recent enrollments for activities
        $recentEnrollments = Enrollment::whereIn('course_id', $instructorCourses)
            ->with(['student', 'course'])
            ->orderBy('enrolled_at', 'desc')
            ->limit(5)
            ->get();
        
        // Format activities from real data
        $activities = $recentEnrollments->map(function($enrollment) {
            $timeAgo = Carbon::parse($enrollment->enrolled_at)->diffForHumans();
            return [
                'title' => 'New Student Enrolled',
                'desc' => ($enrollment->student->name ?? 'Student') . ' joined "' . ($enrollment->course->title ?? 'Course') . '"',
                'time' => $timeAgo
            ];
        })->toArray();
        
        // Enrollment chart data (last 7 days)
        $chartData = [];
        $chartLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $chartLabels[] = $date->format('D');
            
            $scheduled = Enrollment::whereIn('course_id', $instructorCourses)
                ->whereDate('enrolled_at', $date->format('Y-m-d'))
                ->count();
            
            $completed = Enrollment::whereIn('course_id', $instructorCourses)
                ->whereDate('enrolled_at', $date->format('Y-m-d'))
                ->where('status', 'completed')
                ->count();
            
            $chartData['scheduled'][] = $scheduled;
            $chartData['completed'][] = $completed;
        }

        return view('web.instructor.dashboard', compact(
            'totalCourses',
            'activeStudents',
            'totalRevenue',
            'newEnrollments',
            'pendingEnrollments',
            'revenueChange',
            'studentsChange',
            'newCoursesThisMonth',
            'topCourses',
            'activities',
            'chartLabels',
            'chartData'
        ));
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
