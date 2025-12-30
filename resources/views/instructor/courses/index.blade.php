@extends('web.layouts.dashboard_master')

@section('title', 'My Courses')
@section('header_title', 'Courses Management')

@section('content')
<div class="row">
    <div class="col-md-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-gray-800">My Clinical Courses</h5>
            <a href="{{ route('instructor.courses.create.' . app()->getLocale()) }}" class="btn btn-primary shadow-sm">
                <i class="las la-plus-circle"></i> Create New Course
            </a>
        </div>
    </div>

    <!-- Course List -->
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 10%;">Thumbnail</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Students</th>
                                <th>Status</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($courses as $course)
                            <tr>
                                <td>{{ $course->id }}</td>
                                <td>
                                    @if($course->thumbnail)
                                        <img src="{{ asset('storage/'.$course->thumbnail) }}" alt="img" class="img-fluid rounded" style="width: 60px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="bg-gray-200 rounded d-flex align-items-center justify-content-center text-muted" style="width: 60px; height: 40px;">
                                            <i class="las la-image"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <h6 class="mb-0 font-weight-bold">{{ $course->title }}</h6>
                                    <small class="text-muted">{{ Str::limit($course->subtitle, 40) }}</small>
                                </td>
                                <td>{{ $course->category->name ?? 'General' }}</td>
                                <td>{{ $course->price > 0 ? number_format($course->price) . ' EGP' : 'Free' }}</td>
                                <td>{{ $course->enrollments_count ?? 0 }}</td>
                                <td>
                                    @if($course->status == 'published')
                                        <span class="badge badge-success">Published</span>
                                    @elseif($course->status == 'draft')
                                        <span class="badge badge-secondary">Draft</span>
                                    @elseif($course->status == 'review')
                                        <span class="badge badge-warning">Under Review</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="#" class="btn btn-sm btn-light border" title="Edit Content"><i class="las la-edit"></i></a>
                                        <a href="#" class="btn btn-sm btn-light border" title="Curriculum"><i class="las la-list"></i></a>
                                        <a href="#" class="btn btn-sm btn-light border text-danger" title="Delete"><i class="las la-trash"></i></a>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-muted mb-3"><i class="las la-layer-group fa-3x"></i></div>
                                    <h5>No Courses Created Yet</h5>
                                    <p class="text-muted">Start by creating your first clinical course.</p>
                                    <a href="{{ route('instructor.courses.create.' . app()->getLocale()) }}" class="btn btn-outline-primary">Create Course</a>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white">
                <!-- Pagination if needed -->
            </div>
        </div>
    </div>
</div>
@endsection
