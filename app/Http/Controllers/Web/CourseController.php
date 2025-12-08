<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Course::where('status', 'published')
            ->with('instructor')
            ->paginate(12);
        
        return view('web.courses.index', compact('courses'));
    }

    public function show($id)
    {
        $course = Course::with(['lessons', 'instructor'])->findOrFail($id);
        
        return view('web.courses.show', compact('course'));
    }
}
