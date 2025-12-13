@extends('dashboard.layouts.app')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4 text-gray-800">Edit Course: {{ $course->title }}</h1>
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('dashboard.courses.update', $course->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Title</label>
                            <input type="text" name="title" class="form-control" value="{{ $course->title }}" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Price (SAR)</label>
                            <input type="number" name="price" class="form-control" step="0.01" value="{{ $course->price }}" required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Level</label>
                            <select name="level" class="form-control" required>
                                <option value="beginner" {{ $course->level == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                <option value="intermediate" {{ $course->level == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                <option value="advanced" {{ $course->level == 'advanced' ? 'selected' : '' }}>Advanced</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="4">{{ $course->description }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Course Type</label>
                            <select name="type" class="form-control" id="courseType" required>
                                <option value="online" {{ $course->type == 'online' ? 'selected' : '' }}>Online (Video)</option>
                                <option value="offline" {{ $course->type == 'offline' ? 'selected' : '' }}>Offline (In-Person)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6" id="videoUrlField">
                        <div class="form-group">
                            <label>Video URL (Secure)</label>
                            <input type="url" name="video_url" class="form-control" value="{{ $course->video_url }}" placeholder="https://...">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label>Status</label>
                    <select name="status" class="form-control" required>
                        <option value="draft" {{ $course->status == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="pending" {{ $course->status == 'pending' ? 'selected' : '' }}>Pending Review</option>
                        <option value="published" {{ $course->status == 'published' ? 'selected' : '' }}>Published</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Update Course</button>
                <a href="{{ route('dashboard.courses.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
@endsection
