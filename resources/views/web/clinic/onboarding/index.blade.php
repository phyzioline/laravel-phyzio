@extends('web.layouts.dashboard_master')

@section('title', __('Welcome! Let\'s Get Started'))
@section('header_title', __('Welcome to Phyzioline!'))

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Progress Bar -->
            <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <h3 class="font-weight-bold mb-2">{{ __('Welcome to Phyzioline!') }}</h3>
                        <p class="text-muted">{{ __('Let\'s set up your clinic in just a few steps') }}</p>
                    </div>
                    
                    @php
                        $steps = $steps ?? [];
                        $completedSteps = json_decode($clinic->onboarding_completed_steps ?? '[]', true);
                        $totalSteps = count($steps);
                        $completedCount = count($completedSteps);
                        $progress = $totalSteps > 0 ? ($completedCount / $totalSteps) * 100 : 0;
                    @endphp
                    
                    <div class="progress mb-3" style="height: 8px; border-radius: 10px;">
                        <div class="progress-bar bg-success" role="progressbar" 
                             style="width: {{ $progress }}%" 
                             aria-valuenow="{{ $progress }}" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                        </div>
                    </div>
                    <div class="text-center">
                        <small class="text-muted">{{ __('Step') }} {{ $completedCount }} {{ __('of') }} {{ $totalSteps }} {{ __('completed') }}</small>
                    </div>
                </div>
            </div>

            <!-- Steps List -->
            <div class="row">
                @foreach($steps as $key => $step)
                    @php
                        $isCompleted = in_array($key, $completedSteps) || 
                                      ($key === 'specialty' && $clinic->hasSelectedSpecialty()) ||
                                      ($key === 'profile' && $clinic->name && $clinic->phone);
                        $isCurrent = $currentStep === $key;
                    @endphp
                    
                    <div class="col-md-6 mb-4">
                        <div class="card border-0 shadow-sm h-100 {{ $isCurrent ? 'border-primary' : '' }}" 
                             style="border-radius: 15px; {{ $isCurrent ? 'border-width: 2px !important;' : '' }}">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-start">
                                    <div class="mr-3">
                                        @if($isCompleted)
                                            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 50px; height: 50px;">
                                                <i class="las la-check fa-lg"></i>
                                            </div>
                                        @elseif($isCurrent)
                                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 50px; height: 50px;">
                                                <i class="las {{ $step['icon'] }} fa-lg"></i>
                                            </div>
                                        @else
                                            <div class="bg-light text-muted rounded-circle d-flex align-items-center justify-content-center" 
                                                 style="width: 50px; height: 50px;">
                                                <i class="las {{ $step['icon'] }} fa-lg"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-grow-1">
                                        <h5 class="font-weight-bold mb-2">
                                            {{ $step['title'] }}
                                            @if($step['required'] ?? false)
                                                <span class="badge badge-danger ml-2">{{ __('Required') }}</span>
                                            @endif
                                        </h5>
                                        <p class="text-muted mb-3">{{ $step['description'] }}</p>
                                        
                                        @if($isCurrent)
                                            <a href="{{ $step['route'] }}" class="btn btn-primary btn-sm">
                                                <i class="las la-arrow-right"></i> {{ __('Start') }}
                                            </a>
                                        @elseif($isCompleted)
                                            <a href="{{ $step['route'] }}" class="btn btn-outline-secondary btn-sm">
                                                <i class="las la-edit"></i> {{ __('Edit') }}
                                            </a>
                                        @else
                                            <button class="btn btn-outline-secondary btn-sm" disabled>
                                                {{ __('Coming Up') }}
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Actions -->
            <div class="text-center mt-4 mb-4">
                <button onclick="skipOnboarding()" class="btn btn-link text-muted">
                    <i class="las la-times"></i> {{ __('Skip for now') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function skipOnboarding() {
    if (confirm('{{ __("Are you sure you want to skip onboarding? You can complete it later from settings.") }}')) {
        window.location.href = '{{ route("clinic.onboarding.skip") }}';
    }
}
</script>
@endsection

