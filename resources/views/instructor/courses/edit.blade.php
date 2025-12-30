@extends('web.layouts.dashboard_master')

@section('title', 'Edit Course: ' . $course->title)
@section('header_title', 'Edit Course Basics')

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
                        <a class="nav-link" href="{{ route('instructor.courses.edit.' . app()->getLocale(), ['course' => $course->id, 'step' => 'curriculum']) }}">2. Curriculum & Cases</a>
                    </li>
                    <li class="nav-item">
                         <a class="nav-link disabled" href="#">3. Publish</a>
                    </li>
                </ul>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('instructor.courses.update.' . app()->getLocale(), $course->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="text-primary mb-3">Basic Information</h5>
                            
                            <div class="form-group mb-3">
                                <label>Course Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" value="{{ $course->title }}" required>
                            </div>

                            <div class="form-group mb-3">
                                <label>Clinical Specialty <span class="text-danger">*</span></label>
                                <select name="specialty" class="form-control" required>
                                    @foreach(['Orthopedic', 'Neurological', 'Pediatric', 'Sports', 'Cardiopulmonary', 'Geriatric', 'Womens Health'] as $spec)
                                        <option value="{{ $spec }}" {{ $course->specialty == $spec ? 'selected' : '' }}>{{ $spec }}</option>
                                    @endforeach
                                </select>
                            </div>

                             <div class="form-group mb-3">
                                <label>Level <span class="text-danger">*</span></label>
                                <select name="level" class="form-control" required>
                                    @foreach(['student', 'junior', 'senior', 'consultant'] as $lvl)
                                        <option value="{{ $lvl }}" {{ $course->level == $lvl ? 'selected' : '' }}>{{ ucfirst($lvl) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label>Category</label>
                                <select name="category_id" class="form-control" required>
                                    {{-- Assuming category relation exists --}}
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $course->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label>Course Description & Clinical Focus <span class="text-danger">*</span></label>
                                <textarea name="description" class="form-control" rows="5" required>{{ $course->description }}</textarea>
                            </div>
                            
                            {{-- Equipment Logic (Simple Array Impl) --}}
                            <div class="form-group mb-3">
                                <label>Equipment Used</label>
                                <select name="equipment_required[]" class="form-control" multiple>
                                    @php $selectedEq = $course->equipment_required ?? []; @endphp
                                    @foreach(['Treatment Table', 'Theraband', 'Dumbbells', 'Ultrasound', 'TENS', 'Taping', 'Dry Needles'] as $eq)
                                        <option value="{{ $eq }}" {{ in_array($eq, $selectedEq) ? 'selected' : '' }}>{{ $eq }}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted">Hold CTRL to select multiple</small>
                            </div>

                        </div>
                        <div class="col-md-4">
                            <h5 class="text-primary mb-3">Skills & Media</h5>

                            <div class="form-group mb-3">
                                <label>Target Skills</label>
                                <div class="card p-2" style="max-height: 200px; overflow-y: auto;">
                                    @foreach($skills as $skill)
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" name="skills[]" value="{{ $skill->id }}" 
                                                class="custom-control-input" id="skill_{{ $skill->id }}"
                                                {{ $course->skills->contains($skill->id) ? 'checked' : '' }}>
                                            <label class="custom-control-label" for="skill_{{ $skill->id }}">{{ $skill->skill_name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label>Price (EGP)</label>
                                <input type="number" name="price" class="form-control" min="0" value="{{ $course->price }}" required>
                            </div>

                        </div>
                    </div>

                    <div class="text-right mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
