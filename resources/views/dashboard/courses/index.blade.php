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
                                <span class="badge badge-{{ $course->status == 'published' ? 'success' : ($course->status == 'pending' ? 'warning' : 'secondary') }}">
                                    {{ ucfirst($course->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="#" class="btn btn-info btn-sm">View</a>
                                <form action="{{ route('dashboard.courses.update', $course->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PUT')
                                    @if($course->status == 'pending')
                                        <input type="hidden" name="status" value="published">
                                        <button class="btn btn-success btn-sm">Approve</button>
                                    @elseif($course->status == 'published')
                                        <input type="hidden" name="status" value="draft">
                                        <button class="btn btn-warning btn-sm">Unpublish</button>
                                    @endif
                                </form>
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
