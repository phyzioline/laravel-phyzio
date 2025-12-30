@extends('therapist.layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Edit Course: {{ $course->title }}</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('instructor.' . app()->getLocale() . '.courses.update.' . app()->getLocale(), $course->id) }}" method="POST" enctype="multipart/form-data">
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
                    <label>Total Seats (Quota)</label>
                    <input type="number" name="seats" class="form-control" value="{{ old('seats', $course->seats) }}" placeholder="Subject to availability">
                    @error('seats') <span class="text-danger">{{ $message }}</span> @enderror
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

                <div class="col-md-6 mb-3">
                    <label>Course Type</label>
                    <select name="type" class="form-control" required onchange="toggleVideoUrl(this.value)">
                        <option value="online" {{ old('type', $course->type ?? '') == 'online' ? 'selected' : '' }}>Online (Video)</option>
                        <option value="offline" {{ old('type', $course->type ?? '') == 'offline' ? 'selected' : '' }}>Offline (In-person)</option>
                    </select>
                </div>

                <div class="col-md-12 mb-3" id="videoUrlDiv">
                    <label>Video URL</label>
                    <input type="url" name="video_url" class="form-control" value="{{ old('video_url', $course->video_url ?? '') }}" placeholder="https://youtube.com/...">
                </div>

<script>
function toggleVideoUrl(type) {
    document.getElementById('videoUrlDiv').style.display = type === 'online' ? 'block' : 'none';
}
// Initial run
document.addEventListener('DOMContentLoaded', function() {
    toggleVideoUrl(document.querySelector('select[name="type"]').value);
});
</script>

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

                <div class="col-md-6 mb-3">
                    <label>Specialty</label>
                    <input type="text" name="specialty" class="form-control" value="{{ old('specialty', $course->specialty) }}" placeholder="e.g. Orthopedics, Neuro">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Clinical Focus</label>
                    <input type="text" name="clinical_focus" class="form-control" value="{{ old('clinical_focus', $course->clinical_focus) }}" placeholder="e.g. Post-op ACL Rehab">
                </div>

                <div class="col-md-6 mb-3">
                    <label>Accreditation Status</label>
                    <input type="text" name="accreditation_status" class="form-control" value="{{ old('accreditation_status', $course->accreditation_status) }}" placeholder="e.g. CPD Accredited">
                </div>

                <div class="col-md-3 mb-3">
                    <label>Practical Hours</label>
                    <input type="number" step="0.1" name="practical_hours" class="form-control" value="{{ old('practical_hours', $course->practical_hours) }}">
                </div>

                <div class="col-md-3 mb-3">
                    <label>Total Hours</label>
                    <input type="number" step="0.1" name="total_hours" class="form-control" value="{{ old('total_hours', $course->total_hours) }}">
                </div>

                <div class="col-md-12 mb-3">
                    <label>Status</label>
                    <select name="status" class="form-control" required>
                        <option value="draft" {{ old('status', $course->status) == 'draft' ? 'selected' : '' }}>Draft (Hidden)</option>
                        <option value="published" {{ old('status', $course->status) == 'published' ? 'selected' : '' }}>Published (Visible)</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Update Course</button>
            <a href="{{ route('instructor.' . app()->getLocale() . '.courses.index.' . app()->getLocale()) }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
