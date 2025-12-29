@extends('dashboard.layouts.app')
@section('title', __('Review User Verification'))

@section('content')
<main class="main-wrapper">
    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="mb-0 text-uppercase">{{ __('Review Verification') }} - {{ $user->name }}</h6>
            <a href="{{ route('dashboard.verifications.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> {{ __('Back') }}
            </a>
        </div>
        <hr>

        <!-- User Info -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-user mr-2"></i>{{ __('User Information') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>{{ __('Name:') }}</strong> {{ $user->name }}</p>
                        <p><strong>{{ __('Email:') }}</strong> {{ $user->email }}</p>
                        <p><strong>{{ __('Phone:') }}</strong> {{ $user->phone ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>{{ __('Type:') }}</strong> <span class="badge bg-info">{{ ucfirst($user->type) }}</span></p>
                        <p><strong>{{ __('Status:') }}</strong> 
                            @if($user->verification_status === 'pending')
                                <span class="badge bg-secondary">{{ __('Pending') }}</span>
                            @elseif($user->verification_status === 'under_review')
                                <span class="badge bg-warning">{{ __('Under Review') }}</span>
                            @elseif($user->verification_status === 'approved')
                                <span class="badge bg-success">{{ __('Approved') }}</span>
                            @elseif($user->verification_status === 'rejected')
                                <span class="badge bg-danger">{{ __('Rejected') }}</span>
                            @endif
                        </p>
                        <p><strong>{{ __('Progress:') }}</strong> {{ $user->getVerificationProgress() }}%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Documents Review -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-file-alt mr-2"></i>{{ __('Documents Review') }}</h5>
            </div>
            <div class="card-body">
                @foreach($requiredDocuments as $doc)
                    @php
                        $userDoc = $userDocuments->get($doc->document_type);
                        $status = $userDoc ? $userDoc->status : 'missing';
                    @endphp
                    <div class="card mb-3 border-{{ $status === 'approved' ? 'success' : ($status === 'rejected' ? 'danger' : ($status === 'under_review' ? 'warning' : 'secondary')) }}">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <h5 class="mb-1">
                                        {{ $doc->label }}
                                        @if($doc->mandatory)
                                            <span class="badge bg-danger">{{ __('Required') }}</span>
                                        @else
                                            <span class="badge bg-secondary">{{ __('Optional') }}</span>
                                        @endif
                                    </h5>
                                    @if($doc->description)
                                        <p class="text-muted mb-0">{{ $doc->description }}</p>
                                    @endif
                                </div>
                                <div>
                                    @if($status === 'missing')
                                        <span class="badge bg-secondary">{{ __('Missing') }}</span>
                                    @elseif($status === 'uploaded')
                                        <span class="badge bg-info">{{ __('Uploaded') }}</span>
                                    @elseif($status === 'under_review')
                                        <span class="badge bg-warning">{{ __('Under Review') }}</span>
                                    @elseif($status === 'approved')
                                        <span class="badge bg-success">{{ __('Approved') }}</span>
                                    @elseif($status === 'rejected')
                                        <span class="badge bg-danger">{{ __('Rejected') }}</span>
                                    @endif
                                </div>
                            </div>

                            @if($userDoc && $userDoc->file_path)
                                <div class="mb-3">
                                    <a href="{{ asset($userDoc->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i> {{ __('View Document') }}
                                    </a>
                                </div>

                                @if($userDoc->admin_note)
                                    <div class="alert alert-{{ $status === 'rejected' ? 'danger' : 'info' }}">
                                        <strong>{{ __('Admin Note:') }}</strong> {{ $userDoc->admin_note }}
                                    </div>
                                @endif

                                @if($status !== 'approved')
                                    <form action="{{ route('dashboard.verifications.review-document', $userDoc->id) }}" method="POST" class="mt-3">
                                        @csrf
                                        <div class="mb-2">
                                            <textarea name="admin_note" class="form-control" rows="2" 
                                                      placeholder="{{ __('Add a note (optional)') }}">{{ $userDoc->admin_note }}</textarea>
                                        </div>
                                        <div class="btn-group">
                                            <button type="submit" name="action" value="approve" class="btn btn-success">
                                                <i class="fas fa-check"></i> {{ __('Approve') }}
                                            </button>
                                            <button type="submit" name="action" value="reject" class="btn btn-danger">
                                                <i class="fas fa-times"></i> {{ __('Reject') }}
                                            </button>
                                        </div>
                                    </form>
                                @endif
                            @else
                                <p class="text-muted mb-0">{{ __('Document not uploaded yet.') }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        @if($user->type === 'therapist' && $therapistProfile)
        <!-- Module Verification Status -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-puzzle-piece mr-2"></i>{{ __('Module Verification Status') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach(['home_visit' => 'Home Visit', 'courses' => 'Courses', 'clinic' => 'Clinic'] as $moduleType => $moduleLabel)
                        @php
                            $moduleVerification = $moduleVerifications ? $moduleVerifications->get($moduleType) : null;
                            $isVerified = $therapistProfile->canAccessModule($moduleType);
                            $status = $moduleVerification ? $moduleVerification->status : 'pending';
                        @endphp
                        <div class="col-md-4 mb-3">
                            <div class="card border-{{ $isVerified ? 'success' : ($status === 'rejected' ? 'danger' : ($status === 'under_review' ? 'warning' : 'secondary')) }}">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="fas fa-{{ $moduleType === 'home_visit' ? 'home' : ($moduleType === 'courses' ? 'book' : 'hospital') }} mr-2"></i>
                                        {{ $moduleLabel }}
                                    </h6>
                                    <p class="mb-2">
                                        @if($isVerified)
                                            <span class="badge bg-success"><i class="fas fa-check"></i> {{ __('Verified') }}</span>
                                        @elseif($status === 'under_review')
                                            <span class="badge bg-warning"><i class="fas fa-clock"></i> {{ __('Under Review') }}</span>
                                        @elseif($status === 'rejected')
                                            <span class="badge bg-danger"><i class="fas fa-times"></i> {{ __('Rejected') }}</span>
                                        @else
                                            <span class="badge bg-secondary"><i class="fas fa-hourglass-half"></i> {{ __('Not Applied') }}</span>
                                        @endif
                                    </p>
                                    @if($moduleVerification && $moduleVerification->admin_note)
                                        <p class="text-muted small mb-2">{{ $moduleVerification->admin_note }}</p>
                                    @endif
                                    @if($moduleVerification && $moduleVerification->verified_at)
                                        <p class="text-muted small mb-0">
                                            <i class="fas fa-calendar"></i> {{ __('Verified') }}: {{ $moduleVerification->verified_at->format('Y-m-d') }}
                                        </p>
                                    @endif
                                    @if(!$isVerified)
                                        <form action="{{ route('dashboard.verifications.review-module', [$user->id, $moduleType]) }}" method="POST" class="mt-2">
                                            @csrf
                                            <div class="mb-2">
                                                <textarea name="admin_note" class="form-control form-control-sm" rows="2" 
                                                          placeholder="{{ __('Add a note (optional)') }}">{{ $moduleVerification ? $moduleVerification->admin_note : '' }}</textarea>
                                            </div>
                                            <div class="btn-group btn-group-sm w-100">
                                                <button type="submit" name="action" value="approve" class="btn btn-success">
                                                    <i class="fas fa-check"></i> {{ __('Approve') }}
                                                </button>
                                                <button type="submit" name="action" value="reject" class="btn btn-danger">
                                                    <i class="fas fa-times"></i> {{ __('Reject') }}
                                                </button>
                                            </div>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        @if($user->type === 'company' && $companyProfile)
        <!-- Company Module Verification Status -->
        <div class="card mb-4">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0"><i class="fas fa-puzzle-piece mr-2"></i>{{ __('Clinic Access Verification') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @php
                        $moduleType = 'clinic';
                        $moduleVerification = $companyModuleVerifications ? $companyModuleVerifications->get($moduleType) : null;
                        $isVerified = $companyProfile->canAccessClinic();
                        $status = $moduleVerification ? $moduleVerification->status : 'pending';
                    @endphp
                    <div class="col-md-4 mb-3">
                        <div class="card border-{{ $isVerified ? 'success' : ($status === 'rejected' ? 'danger' : ($status === 'under_review' ? 'warning' : 'secondary')) }}">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-hospital mr-2"></i>
                                    {{ __('Clinic Access') }}
                                </h6>
                                <p class="mb-2">
                                    @if($isVerified)
                                        <span class="badge bg-success"><i class="fas fa-check"></i> {{ __('Verified') }}</span>
                                    @elseif($status === 'under_review')
                                        <span class="badge bg-warning"><i class="fas fa-clock"></i> {{ __('Under Review') }}</span>
                                    @elseif($status === 'rejected')
                                        <span class="badge bg-danger"><i class="fas fa-times"></i> {{ __('Rejected') }}</span>
                                    @else
                                        <span class="badge bg-secondary"><i class="fas fa-hourglass-half"></i> {{ __('Not Applied') }}</span>
                                    @endif
                                </p>
                                @if($moduleVerification && $moduleVerification->admin_note)
                                    <p class="text-muted small mb-2">{{ $moduleVerification->admin_note }}</p>
                                @endif
                                @if($moduleVerification && $moduleVerification->verified_at)
                                    <p class="text-muted small mb-0">
                                        <i class="fas fa-calendar"></i> {{ __('Verified') }}: {{ $moduleVerification->verified_at->format('Y-m-d') }}
                                    </p>
                                @endif
                                @if(!$isVerified)
                                    <form action="{{ route('dashboard.verifications.review-company-module', [$user->id, $moduleType]) }}" method="POST" class="mt-2">
                                        @csrf
                                        <div class="mb-2">
                                            <textarea name="admin_note" class="form-control form-control-sm" rows="2" 
                                                      placeholder="{{ __('Add a note (optional)') }}">{{ $moduleVerification ? $moduleVerification->admin_note : '' }}</textarea>
                                        </div>
                                        <div class="btn-group btn-group-sm w-100">
                                            <button type="submit" name="action" value="approve" class="btn btn-success">
                                                <i class="fas fa-check"></i> {{ __('Approve') }}
                                            </button>
                                            <button type="submit" name="action" value="reject" class="btn btn-danger">
                                                <i class="fas fa-times"></i> {{ __('Reject') }}
                                            </button>
                                        </div>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-bolt mr-2"></i>{{ __('Quick Actions') }}</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('dashboard.verifications.review-user', $user->id) }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">{{ __('Admin Note') }}</label>
                        <textarea name="admin_note" class="form-control" rows="3" 
                                  placeholder="{{ __('Add a note about this review (optional)') }}"></textarea>
                    </div>
                    <div class="btn-group">
                        <button type="submit" name="action" value="approve" class="btn btn-success btn-lg">
                            <i class="fas fa-check-circle"></i> {{ __('Approve Account') }}
                        </button>
                        <button type="submit" name="action" value="reject" class="btn btn-danger btn-lg">
                            <i class="fas fa-times-circle"></i> {{ __('Reject Account') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>
@endsection

