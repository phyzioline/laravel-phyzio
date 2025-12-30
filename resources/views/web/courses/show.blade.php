@extends('web.layouts.app')

@section('title', $course->title . ' - Phyzioline Academy')

@section('content')
<!-- Hero Section -->
<div class="bg-dark text-white position-relative" style="background: linear-gradient(rgba(0,0,0,0.8), rgba(0,0,0,0.8)), url('{{ $course->thumbnail ? Storage::url($course->thumbnail) : 'https://via.placeholder.com/1920x600' }}') no-repeat center center/cover;">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-lg-8 py-5">
                <span class="badge badge-warning mb-2">{{ $course->level }}</span>
                @if($course->specialty)
                    <span class="badge badge-info mb-2 ml-2">{{ $course->specialty }}</span>
                @endif
                @if($course->clinical_focus)
                    <span class="badge badge-primary mb-2 ml-2">{{ $course->clinical_focus }}</span>
                @endif
                <h1 class="display-4 font-weight-bold">{{ $course->title }}</h1>
                <p class="lead mb-4">{{ Str::limit(strip_tags($course->description), 150) }}</p>
                
                <div class="d-flex align-items-center mb-4">
                    <img src="{{ $course->instructor->image_url ?? 'https://ui-avatars.com/api/?name='.urlencode($course->instructor->name) }}" class="rounded-circle mr-3" width="50" height="50" style="border: 2px solid #fff;">
                    <div>
                        <div class="small text-white-50">{{ __('Created by') }}</div>
                        <a href="#" class="text-white font-weight-bold">{{ $course->instructor->name }}</a>
                    </div>
                    <div class="ml-5">
                        <div class="small text-white-50">{{ __('Last Updated') }}</div>
                        <div class="font-weight-bold">{{ $course->updated_at->format('M Y') }}</div>
                    </div>
                </div>

                <div class="d-flex align-items-center">
                    <div class="mr-4">
                        <i class="las la-star text-warning"></i>
                        <span class="font-weight-bold">4.8</span>
                        <span class="small text-white-50">(1,240 ratings)</span>
                    </div>
                    <div>
                         <i class="las la-user-graduate text-light"></i>
                        <span class="font-weight-bold">5,400</span>
                        <span class="small text-white-50">students</span>
                    </div>
                    <div class="ml-4">
                         <i class="las la-globe text-light"></i>
                        <span class="font-weight-bold">{{ $course->language }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container my-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Learning Outcomes -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body">
                    <h4 class="font-weight-bold mb-4">{{ __('What you\'ll learn') }}</h4>
                     <div class="row">
                         <!-- Mock Outcomes if JSON is empty/null for mockup -->
                         @php 
                            $outcomes = $course->outcomes ?? [
                                'Master the fundamentals of physical therapy assessment.',
                                'Understand advanced manual therapy techniques.',
                                'Develop comprehensive treatment plans.',
                                'Identify red flags and contraindications.'
                            ];
                         @endphp
                         @foreach($outcomes as $outcome)
                            <div class="col-md-6 mb-2">
                                <div class="d-flex">
                                    <i class="las la-check text-success mr-2 mt-1"></i>
                                    <span>{{ $outcome }}</span>
                                </div>
                            </div>
                         @endforeach
                     </div>
                </div>
            </div>

            <!-- Verified Clinical Skills (New) -->
            @if($course->skills && $course->skills->count() > 0)
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h5 class="font-weight-bold mb-0 text-primary">{{ __('Verified Clinical Skills') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Skill Name</th>
                                        <th>Risk Level</th>
                                        <th>Mastery Required</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($course->skills as $skill)
                                        <tr>
                                            <td class="font-weight-bold">{{ $skill->skill_name }}</td>
                                            <td>
                                                <span class="badge badge-{{ $skill->risk_level == 'high' ? 'danger' : ($skill->risk_level == 'medium' ? 'warning' : 'success') }}">
                                                    {{ ucfirst($skill->risk_level) }} Risk
                                                </span>
                                            </td>
                                            <td>{{ ucfirst($skill->pivot->mastery_level_required) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Course Content / Curriculum -->
            <div class="mb-5">
                <h4 class="font-weight-bold mb-3">{{ __('Clinical Curriculum') }}</h4>
                <div class="d-flex justify-content-between mb-2 small text-muted">
                    <span>{{ $course->modules->count() }} modules</span>
                    <span>{{ $course->total_hours }}h total clinical practice</span>
                </div>
                
                <div class="accordion" id="accordionCurriculum">
                    @forelse($course->modules as $module)
                        <div class="card">
                            <div class="card-header bg-light" id="heading{{ $module->id }}">
                                 <h2 class="mb-0">
                                    <button class="btn btn-link btn-block text-left text-dark font-weight-bold text-decoration-none" type="button" data-toggle="collapse" data-target="#collapse{{ $module->id }}">
                                        {{ $module->title }}
                                    </button>
                                </h2>
                            </div>
                            <div id="collapse{{ $module->id }}" class="collapse {{ $loop->first ? 'show' : '' }}" data-parent="#accordionCurriculum">
                                <div class="card-body p-0">
                                    <ul class="list-group list-group-flush">
                                        @forelse($module->units as $unit)
                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                <div>
                                                    <i class="las {{ $unit->unit_type == 'case' ? 'la-user-injured text-danger' : 'la-play-circle text-muted' }} mr-2"></i>
                                                    {{ $unit->title }}
                                                    @if($unit->unit_type == 'case')
                                                        <span class="badge badge-danger ml-2">Clinical Case</span>
                                                    @endif
                                                </div>
                                                <span class="text-muted small">{{ $unit->duration_minutes }}m</span>
                                            </li>
                                        @empty
                                            <li class="list-group-item text-muted">{{ __('No units in this module.') }}</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="alert alert-light">{{ __('Curriculum is being updated.') }}</div>
                    @endforelse
                </div>
            </div>

            <!-- Description -->
             <div class="mb-5">
                <h4 class="font-weight-bold mb-3">{{ __('Description') }}</h4>
                <div class="prose text-muted">
                    {!! nl2br(e($course->description)) !!}
                </div>
             </div>

            <!-- Instructor -->
            <div class="mb-5">
                 <h4 class="font-weight-bold mb-3">{{ __('Instructor') }}</h4>
                 <div class="card bg-light border-0 p-4">
                     <div class="d-flex align-items-start">
                         <img src="{{ $course->instructor->image_url ?? 'https://ui-avatars.com/api/?name='.urlencode($course->instructor->name) }}" class="rounded-circle mr-4" width="80" height="80">
                         <div>
                             <h5 class="font-weight-bold mb-1"><a href="#" class="text-dark">{{ $course->instructor->name }}</a></h5>
                             <div class="text-muted small mb-3">{{ $course->instructor->specialty ?? 'Specialist Physical Therapist' }}</div>
                             <p class="small text-muted mb-0">
                                 Dr. {{ $course->instructor->name }} is a highly experienced therapist with over 10 years in the field. They specialize in {{ $course->instructor->specialty ?? 'general therapy' }} and have taught thousands of students worldwide.
                             </p>
                         </div>
                     </div>
                 </div>
            </div>
        </div>

        <!-- Sidebar / Pricing -->
        <div class="col-lg-4">
            <div class="card shadow-lg border-0 sticky-top" style="top: 100px; z-index: 10;">
                <div class="position-relative">
                    @if($course->trailer_url)
                        <!-- If trailer exists, show play button overlay -->
                        <div class="bg-dark d-flex align-items-center justify-content-center" style="height: 200px; cursor: pointer;">
                            <i class="las la-play-circle text-white display-3"></i>
                        </div>
                    @else
                         <img src="{{ $course->thumbnail ? Storage::url($course->thumbnail) : 'https://via.placeholder.com/600x400' }}" class="card-img-top">
                    @endif
                </div>
                <div class="card-body p-4">
                    @if($course->accreditation_status)
                        <div class="mb-3 text-center">
                            <span class="badge badge-success p-2 w-100">
                                <i class="las la-check-circle mr-1"></i> {{ $course->accreditation_status }}
                            </span>
                        </div>
                    @endif

                    <div class="mb-3">
                        @if($course->discount_price)
                            <h2 class="font-weight-bold mb-0 text-primary">{{ $course->discount_price }} EGP</h2>
                            <span class="text-muted"><del>{{ $course->price }} EGP</del></span>
                            <span class="text-danger ml-2">{{ round((($course->price - $course->discount_price) / $course->price) * 100) }}% off</span>
                        @elseif($course->price > 0)
                            <h2 class="font-weight-bold mb-0 text-primary">{{ $course->price }} EGP</h2>
                        @else
                            <h2 class="font-weight-bold mb-0 text-success">{{ __('Free') }}</h2>
                        @endif
                    </div>
                    
                    @if($isEnrolled)
                         <a href="#" class="btn btn-success btn-block btn-lg mb-3 shadow-sm">{{ __('Go to Course') }}</a>
                    @else
                         <form action="{{ route('web.courses.purchase', $course->id) }}" method="POST">
                             @csrf
                             <button type="submit" class="btn btn-primary btn-block btn-lg mb-3 shadow-sm" style="background-color: #02767F; border-color: #02767F;">
                                 {{ $course->price > 0 ? __('Buy Now') : __('Enroll for Free') }}
                             </button>
                         </form>
                         <button class="btn btn-outline-secondary btn-block mb-4">{{ __('Add to Cart') }}</button>
                    @endif

                    <div class="small">
                        <p class="mb-2"><i class="las la-video mr-2"></i> {{ $course->total_hours }}h clinical practice</p>
                        <p class="mb-2"><i class="las la-file-download mr-2"></i> {{ $course->modules->count() }} modules</p>
                        <p class="mb-2"><i class="las la-infinity mr-2"></i> Lifetime access</p>
                        
                        @if($course->equipment_required)
                            <hr>
                            <h6 class="font-weight-bold text-muted mb-2">{{ __('Required Equipment:') }}</h6>
                            <ul class="list-unstyled text-muted small">
                                @foreach($course->equipment_required as $equipment)
                                    <li><i class="las la-tools mr-1"></i> {{ $equipment }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
