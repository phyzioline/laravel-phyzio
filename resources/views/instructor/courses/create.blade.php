@extends('web.layouts.dashboard_master')

@section('title', 'Create New Clinical Course')
@section('header_title', 'Create New Clinical Course')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <ul class="nav nav-pills card-header-pills">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">1. Course Basics</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#">2. Curriculum & Cases</a>
                    </li>
                    <li class="nav-item">
                         <a class="nav-link disabled" href="#">3. Publish</a>
                    </li>
                </ul>
            </div>
            <div class="card-body p-4">
                @if($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger mb-4">
                        {{ session('error') }}
                    </div>
                @endif
                
                @if(session('success'))
                    <div class="alert alert-success mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                
                <form action="{{ route('instructor.' . app()->getLocale() . '.courses.store.' . app()->getLocale()) }}" method="POST" enctype="multipart/form-data" id="courseCreateForm">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="text-primary mb-3">Basic Information</h5>
                            
                            <div class="form-group mb-3">
                                <label>Course Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" placeholder="e.g. Advanced Manual Therapy for Cervical Spine" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Clinical Specialty <span class="text-danger">*</span></label>
                                <select name="specialty" class="form-control @error('specialty') is-invalid @enderror" required>
                                    <option value="">Select Specialty</option>
                                    <option value="Orthopedic" {{ old('specialty') == 'Orthopedic' ? 'selected' : '' }}>Orthopedic / Musculoskeletal</option>
                                    <option value="Neurological" {{ old('specialty') == 'Neurological' ? 'selected' : '' }}>Neurological</option>
                                    <option value="Pediatric" {{ old('specialty') == 'Pediatric' ? 'selected' : '' }}>Pediatric</option>
                                    <option value="Sports" {{ old('specialty') == 'Sports' ? 'selected' : '' }}>Sports Rehab</option>
                                    <option value="Cardiopulmonary" {{ old('specialty') == 'Cardiopulmonary' ? 'selected' : '' }}>Cardiopulmonary</option>
                                    <option value="Geriatric" {{ old('specialty') == 'Geriatric' ? 'selected' : '' }}>Geriatric</option>
                                    <option value="Womens Health" {{ old('specialty') == 'Womens Health' ? 'selected' : '' }}>Women's Health</option>
                                </select>
                                @error('specialty')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                             <div class="form-group mb-3">
                                <label>Level <span class="text-danger">*</span></label>
                                <select name="level" class="form-control @error('level') is-invalid @enderror" required>
                                    <option value="student" {{ old('level') == 'student' ? 'selected' : '' }}>Student (Undergraduate)</option>
                                    <option value="junior" {{ old('level') == 'junior' ? 'selected' : '' }}>Junior (0-3 Years)</option>
                                    <option value="senior" {{ old('level') == 'senior' ? 'selected' : '' }}>Senior (3-7 Years)</option>
                                    <option value="consultant" {{ old('level') == 'consultant' ? 'selected' : '' }}>Consultant / Expert</option>
                                </select>
                                @error('level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label>Category <span class="text-danger">*</span></label>
                                <select name="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                                    <option value="">{{ __('Select Category') }}</option>
                                    @foreach($categories->where('status', 'active') as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->{'name_' . app()->getLocale()} ?? $category->name_en }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($categories->where('status', 'active')->isEmpty())
                                    <small class="text-warning">{{ __('No active categories available. Please contact administrator.') }}</small>
                                @endif
                            </div>

                            <div class="form-group mb-3">
                                <label>Course Description & Clinical Focus <span class="text-danger">*</span></label>
                                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="5" required placeholder="Describe what the therapist will learn and apply...">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="form-group mb-3">
                                <label>Equipment Used (Select Multiple)</label>
                                <select name="equipment_required[]" class="form-control" multiple>
                                    <option value="Treatment Table">Treatment Table</option>
                                    <option value="Theraband">Theraband / Resistance Bands</option>
                                    <option value="Dumbbells">Dumbbells</option>
                                    <option value="Ultrasound">Ultrasound Device</option>
                                    <option value="TENS">TENS / Electrotherapy</option>
                                    <option value="Taping">Kinesiotape</option>
                                    <option value="Dry Needles">Dry Needles</option>
                                </select>
                                <small class="text-muted">Hold CTRL to select multiple</small>
                            </div>

                        </div>
                        <div class="col-md-4">
                            <h5 class="text-primary mb-3">Skills & Media</h5>

                            <div class="form-group mb-3">
                                <label>Target Skills (What does this course certify?)</label>
                                <div class="card p-2" style="max-height: 200px; overflow-y: auto;">
                                    @foreach($skills as $skill)
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="skills[]" value="{{ $skill->id }}" class="custom-control-input" id="skill_{{ $skill->id }}">
                                            <label class="custom-control-label" for="skill_{{ $skill->id }}">{{ $skill->skill_name }} ({{ $skill->risk_level }})</label>
                                        </div>
                                    @endforeach
                                </div>
                                <small class="text-muted"><a href="#">+ Request New Skill</a></small>
                            </div>

                            <div class="form-group mb-3">
                                <label>Course Format</label>
                                <select name="type" class="form-control" required>
                                    <option value="online">Online (Video Based)</option>
                                    <option value="offline" selected>In-Person / Workshop</option>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label>Total Seats (Quota)</label>
                                <input type="number" name="seats" class="form-control" min="0" placeholder="e.g. 25 (Leave empty for unlimited)">
                                <small class="text-muted">For Offline workshops</small>
                            </div>

                            <div class="form-group mb-3">
                                <label>Price (EGP)</label>
                                <input type="number" name="price" class="form-control" min="0" value="0" required>
                                <small class="text-muted">Set to 0 for Free</small>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label>Intro Video URL (YouTube/Vimeo)</label>
                                <input type="url" name="trailer_url" class="form-control" placeholder="https://youtube.com/...">
                            </div>

                            <div class="form-group mb-3">
                                <label>Thumbnail Image</label>
                                <input type="file" name="thumbnail" class="form-control-file">
                            </div>

                        </div>
                    </div>

                    <div class="text-right mt-4">
                        <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                            Next: Build Curriculum <i class="las la-arrow-right"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('courseCreateForm');
    const submitBtn = document.getElementById('submitBtn');
    
    if (form && submitBtn) {
        form.addEventListener('submit', function(e) {
            // Don't prevent default - let form submit normally
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="las la-spinner la-spin"></i> Creating...';
        });
    }
});
</script>
@endpush
@endsection
