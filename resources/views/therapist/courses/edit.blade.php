@extends('therapist.layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Edit Course: {{ $course->title }}</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('therapist.courses.update', $course->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label>Course Title</label>
                    <input type="text" name="title" class="form-control" required value="{{ old('title', $course->title) }}">
                    @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-12 mb-3">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="4" required>{{ old('description', $course->description) }}</textarea>
                    @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label>Price ($)</label>
                    <input type="number" step="0.01" name="price" class="form-control" required value="{{ old('price', $course->price) }}">
                    @error('price') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label>Level</label>
                    <select name="level" class="form-control" required>
                        <option value="">Select Level</option>
                        <option value="beginner" {{ old('level', $course->level) == 'beginner' ? 'selected' : '' }}>Beginner</option>
                        <option value="intermediate" {{ old('level', $course->level) == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                        <option value="advanced" {{ old('level', $course->level) == 'advanced' ? 'selected' : '' }}>Advanced</option>
                    </select>
                    @error('level') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-12 mb-3">
                    <label>Thumbnail</label>
                    @if($course->thumbnail)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="Thumbnail" width="100">
                        </div>
                    @endif
                    <input type="file" name="thumbnail" class="form-control">
                    @error('thumbnail') <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update Course</button>
            <a href="{{ route('therapist.courses.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
