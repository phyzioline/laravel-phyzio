@extends('therapist.layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Create New Course</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('instructor.courses.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="row">
                <div class="col-md-12 mb-3">
                    <label>Course Title</label>
                    <input type="text" name="title" class="form-control" required value="{{ old('title') }}">
                    @error('title') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-12 mb-3">
                    <label>Description</label>
                    <textarea name="description" class="form-control" rows="4" required>{{ old('description') }}</textarea>
                    @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label>Price ($)</label>
                    <input type="number" step="0.01" name="price" class="form-control" required value="{{ old('price') }}">
                    @error('price') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label>Total Seats (Quota)</label>
                    <input type="number" name="seats" class="form-control" value="{{ old('seats') }}" placeholder="Subject to availability">
                    @error('seats') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label>Level</label>
                    <select name="level" class="form-control" required>
                        <option value="">Select Level</option>
                        <option value="beginner" {{ old('level') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                        <option value="intermediate" {{ old('level') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                        <option value="advanced" {{ old('level') == 'advanced' ? 'selected' : '' }}>Advanced</option>
                    </select>
                    @error('level') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-6 mb-3">
                    <label>Course Type</label>
                    <select name="type" class="form-control" required onchange="toggleVideoUrl(this.value)">
                        <option value="online" {{ old('type') == 'online' ? 'selected' : '' }}>Online (Video)</option>
                        <option value="offline" {{ old('type') == 'offline' ? 'selected' : '' }}>Offline (In-person)</option>
                    </select>
                </div>

                <div class="col-md-12 mb-3" id="videoUrlDiv">
                    <label>Video URL</label>
                    <input type="url" name="video_url" class="form-control" value="{{ old('video_url') }}" placeholder="https://youtube.com/...">
                </div>

                <div class="col-md-12 mb-3" id="videoUploadDiv" style="display:none;">
                    <label>Upload Video (MP4)</label>
                    <input type="file" name="video_file" class="form-control" accept="video/mp4,video/x-m4v,video/*">
                </div>

<script>
function toggleVideoUrl(type) {
    if(type === 'online') {
         document.getElementById('videoUrlDiv').style.display = 'block';
         document.getElementById('videoUploadDiv').style.display = 'block'; // Allow both or toggle? 
         // For now show both options if online
    } else {
         document.getElementById('videoUrlDiv').style.display = 'none';
         document.getElementById('videoUploadDiv').style.display = 'none';
    }
}
// Initial run
document.addEventListener('DOMContentLoaded', function() {
    toggleVideoUrl(document.querySelector('select[name="type"]').value);
});
</script>

                <div class="col-md-12 mb-3">
                    <label>Thumbnail</label>
                    <input type="file" name="thumbnail" class="form-control">
                    @error('thumbnail') <span class="text-danger">{{ $message }}</span> @enderror
                </div>

                <div class="col-md-12 mb-3">
                    <label>Status</label>
                    <select name="status" class="form-control" required>
                        <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft (Hidden)</option>
                        <option value="published" {{ old('status') == 'published' ? 'selected' : '' }} selected>Published (Visible)</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-primary">Create Course</button>
            <a href="{{ route('instructor.courses.index') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
