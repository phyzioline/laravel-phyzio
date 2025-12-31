@php
    $user = Auth::user();
    $progress = $user->getVerificationProgress();
    $isVerified = $user->isVerified();
@endphp

<div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
    <div class="card-header bg-white border-0 pt-4 px-4">
        <h5 class="card-title font-weight-bold mb-0">
            <i class="fas fa-heartbeat text-danger mr-2"></i>{{ __('Account Health') }}
        </h5>
    </div>
    <div class="card-body px-4">
        @if($isVerified)
            <div class="text-center mb-3">
                <div class="mb-2">
                    <i class="fas fa-check-circle fa-3x text-success"></i>
                </div>
                <h4 class="text-success font-weight-bold mb-1">{{ __('Account Active') }}</h4>
                <p class="text-muted mb-0">{{ __('Your account is verified and visible to users') }}</p>
            </div>
        @else
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-2">
                    <span class="font-weight-bold">{{ __('Verification Progress') }}</span>
                    <span class="font-weight-bold text-primary">{{ $progress }}%</span>
                </div>
                <div class="progress" style="height: 25px; border-radius: 10px;">
                    <div class="progress-bar bg-{{ $progress == 100 ? 'success' : 'warning' }} progress-bar-striped progress-bar-animated" 
                         role="progressbar" 
                         style="width: {{ $progress }}%" 
                         aria-valuenow="{{ $progress }}" 
                         aria-valuemin="0" 
                         aria-valuemax="100">
                        {{ $progress }}%
                    </div>
                </div>
            </div>
        @endif

        <div class="row text-center mt-3">
            <div class="col-6">
                <div class="border-right">
                    <h3 class="mb-0 font-weight-bold text-primary">{{ $progress }}%</h3>
                    <small class="text-muted">{{ __('Verified') }}</small>
                </div>
            </div>
            <div class="col-6">
                <h3 class="mb-0 font-weight-bold text-info">
                    @if($user->verification_status === 'approved')
                        <i class="fas fa-check text-success"></i> 100%
                    @else
                        <i class="fas fa-clock text-warning"></i> {{ $progress }}%
                    @endif
                </h3>
                <small class="text-muted">{{ __('Status') }}</small>
            </div>
        </div>

        @if(!$isVerified)
            <div class="mt-3">
                <a href="{{ route('verification.verification-center.' . app()->getLocale()) }}" class="btn btn-primary btn-block">
                    <i class="fas fa-upload mr-2"></i>{{ __('Complete Verification') }}
                </a>
            </div>
        @endif
    </div>
</div>

