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
                <form action="{{ route('instructor.courses.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-8">
                            <h5 class="text-primary mb-3">Basic Information</h5>
                            
                            <div class="form-group mb-3">
                                <label>Course Title <span class="text-danger">*</span></label>
                                <input type="text" name="title" class="form-control" placeholder="e.g. Advanced Manual Therapy for Cervical Spine" required>
                            </div>

                            <div class="form-group mb-3">
                                <label>Clinical Specialty <span class="text-danger">*</span></label>
                                <select name="specialty" class="form-control" required>
                                    <option value="">Select Specialty</option>
                                    <option value="Orthopedic">Orthopedic / Musculoskeletal</option>
                                    <option value="Neurological">Neurological</option>
                                    <option value="Pediatric">Pediatric</option>
                                    <option value="Sports">Sports Rehab</option>
                                    <option value="Cardiopulmonary">Cardiopulmonary</option>
                                    <option value="Geriatric">Geriatric</option>
                                    <option value="Womens Health">Women's Health</option>
                                </select>
                            </div>

                             <div class="form-group mb-3">
                                <label>Level <span class="text-danger">*</span></label>
                                <select name="level" class="form-control" required>
                                    <option value="student">Student (Undergraduate)</option>
                                    <option value="junior">Junior (0-3 Years)</option>
                                    <option value="senior">Senior (3-7 Years)</option>
                                    <option value="consultant">Consultant / Expert</option>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label>Category</label>
                                <select name="category_id" class="form-control" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label>Course Description & Clinical Focus <span class="text-danger">*</span></label>
                                <textarea name="description" class="form-control" rows="5" required placeholder="Describe what the therapist will learn and apply..."></textarea>
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
                                <label>Price (EGP)</label>
                                <input type="number" name="price" class="form-control" min="0" value="0" required>
                                <small class="text-muted">Set to 0 for Free</small>
                            </div>
                            
                            <div class="form-group mb-3">
                                <label>Thumbnail Image</label>
                                <input type="file" name="thumbnail" class="form-control-file">
                            </div>

                        </div>
                    </div>

                    <div class="text-right mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">Next: Build Curriculum <i class="las la-arrow-right"></i></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
