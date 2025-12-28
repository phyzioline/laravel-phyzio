@extends('dashboard.layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Courses</h1>
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">All Courses</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Instructor</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tbody>
                        @foreach($courses as $course)
                        <tr>
                            <td>{{ $course->id }}</td>
                            <td>{{ $course->title }}</td>
                            <td>{{ $course->instructor->user->name ?? 'N/A' }}</td>
                            <td>{{ $course->price }} SAR</td>
                            <td>
                                @if($course->status == 'published')
                                    <span class="badge badge-success">Published</span>
                                @elseif($course->status == 'review')
                                    <span class="badge badge-warning">Pending Review</span>
                                @else
                                    <span class="badge badge-secondary">Draft</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('dashboard.courses.show', $course->id) }}" class="btn btn-info btn-sm">
                                    <i class="las la-eye"></i> View
                                </a>
                                @if($course->status == 'review')
                                    <form action="{{ route('dashboard.courses.update', $course->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="action" value="approve">
                                        <button type="submit" class="btn btn-success btn-sm" title="Approve Course">
                                            <i class="las la-check"></i> Approve
                                        </button>
                                    </form>
                                    <form action="{{ route('dashboard.courses.update', $course->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="action" value="reject">
                                        <button type="submit" class="btn btn-danger btn-sm" title="Reject Course" onclick="return confirm('Are you sure you want to reject this course?')">
                                            <i class="las la-times"></i> Reject
                                        </button>
                                    </form>
                                @elseif($course->status == 'published')
                                    <form action="{{ route('dashboard.courses.update', $course->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="status" value="draft">
                                        <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Are you sure you want to unpublish this course?')">
                                            <i class="las la-ban"></i> Unpublish
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
