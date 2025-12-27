@extends('therapist.layouts.app')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>My Courses</h4>
        <a href="{{ route('instructor.courses.create') }}" class="btn btn-primary">Create New Course</a>
    </div>
    <div class="card-body">
        @if(session('message'))
            <div class="alert alert-{{ session('message')['type'] }}">
                {{ session('message')['text'] }}
            </div>
        @endif

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Price</th>
                        <th>Level</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                        <tr>
                            <td>{{ $course->title }}</td>
                            <td>${{ number_format($course->price, 2) }}</td>
                            <td>{{ ucfirst($course->level) }}</td>
                            <td>
                                <span class="badge bg-{{ $course->status == 'published' ? 'success' : ($course->status == 'draft' ? 'secondary' : 'warning') }}">
                                    {{ ucfirst($course->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('instructor.courses.edit', $course->id) }}" class="btn btn-sm btn-info">Edit</a>
                                <form action="{{ route('instructor.courses.destroy', $course->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">No courses found. Create your first one!</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
