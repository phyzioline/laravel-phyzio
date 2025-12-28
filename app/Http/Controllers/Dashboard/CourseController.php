<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CourseController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('can:courses-index', only: ['index']),
            new Middleware('can:courses-create', only: ['create', 'store']),
            new Middleware('can:courses-show', only: ['show']),
            new Middleware('can:courses-update', only: ['edit', 'update']),
            new Middleware('can:courses-delete', only: ['destroy']),
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\Course::with('instructor');
        
        // Filter by status if provided
        if ($request->has('status')) {
            if ($request->status === 'pending') {
                $query->where('status', 'review');
            } elseif ($request->status === 'approved') {
                $query->where('status', 'published');
            } elseif ($request->status === 'draft') {
                $query->where('status', 'draft');
            }
        }
        
        $courses = $query->orderBy('created_at', 'desc')->get();
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
            'status' => 'sometimes|in:draft,review,published',
            'action' => 'sometimes|in:approve,reject',
        ]);

        // Handle approval actions
        if ($request->has('action')) {
            if ($request->action === 'approve') {
                $course->update(['status' => 'published']);
                return redirect()->route('dashboard.courses.index')
                    ->with('message', ['type' => 'success', 'text' => 'Course approved and published successfully!']);
            } elseif ($request->action === 'reject') {
                $course->update(['status' => 'draft']);
                return redirect()->route('dashboard.courses.index')
                    ->with('message', ['type' => 'success', 'text' => 'Course rejected and moved to draft.']);
            }
        }
        
        // Regular update
        $course->update($request->all());
        return redirect()->route('dashboard.courses.index')
            ->with('message', ['type' => 'success', 'text' => 'Course updated successfully!']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
