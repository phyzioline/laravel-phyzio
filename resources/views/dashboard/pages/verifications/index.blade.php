@extends('dashboard.layouts.app')
@section('title', __('User Verifications'))

@section('content')
<main class="main-wrapper">
    <div class="main-content">
        <h6 class="mb-0 text-uppercase">{{ __('User Verifications') }}</h6>
        <hr>

        <!-- Filters -->
        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('dashboard.verifications.index') }}" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">{{ __('Status') }}</label>
                        <select name="status" class="form-select">
                            <option value="">{{ __('All Statuses') }}</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                            <option value="under_review" {{ request('status') == 'under_review' ? 'selected' : '' }}>{{ __('Under Review') }}</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>{{ __('Rejected') }}</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">{{ __('User Type') }}</label>
                        <select name="type" class="form-select">
                            <option value="">{{ __('All Types') }}</option>
                            <option value="vendor" {{ request('type') == 'vendor' ? 'selected' : '' }}>{{ __('Vendor') }}</option>
                            <option value="company" {{ request('type') == 'company' ? 'selected' : '' }}>{{ __('Company') }}</option>
                            <option value="therapist" {{ request('type') == 'therapist' ? 'selected' : '' }}>{{ __('Therapist') }}</option>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">{{ __('Filter') }}</button>
                        <a href="{{ route('dashboard.verifications.index') }}" class="btn btn-secondary">{{ __('Reset') }}</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Documents') }}</th>
                                <th>{{ __('Progress') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($users as $user)
                                @php
                                    $progress = $user->getVerificationProgress();
                                    $docCount = $user->documents()->count();
                                    $approvedCount = $user->documents()->where('status', 'approved')->count();
                                    
                                    // Check for pending module verifications (for therapists and companies)
                                    $pendingModules = [];
                                    if ($user->type === 'therapist') {
                                        $therapistProfile = \App\Models\TherapistProfile::where('user_id', $user->id)->first();
                                        if ($therapistProfile) {
                                            $moduleVerifications = \App\Models\TherapistModuleVerification::where('therapist_profile_id', $therapistProfile->id)
                                                ->whereIn('status', ['pending', 'under_review'])
                                                ->get();
                                            foreach ($moduleVerifications as $mv) {
                                                $pendingModules[] = ucfirst(str_replace('_', ' ', $mv->module_type));
                                            }
                                        }
                                    } elseif ($user->type === 'company') {
                                        $companyProfile = \App\Models\CompanyProfile::where('user_id', $user->id)->first();
                                        if ($companyProfile) {
                                            $moduleVerifications = \App\Models\CompanyModuleVerification::where('company_profile_id', $companyProfile->id)
                                                ->whereIn('status', ['pending', 'under_review'])
                                                ->get();
                                            foreach ($moduleVerifications as $mv) {
                                                $pendingModules[] = ucfirst(str_replace('_', ' ', $mv->module_type));
                                            }
                                        }
                                    }
                                @endphp
                                <tr class="{{ !empty($pendingModules) ? 'table-warning' : '' }}">
                                    <td>{{ $user->id }}</td>
                                    <td>
                                        {{ $user->name }}
                                        @if(!empty($pendingModules))
                                            <span class="badge bg-warning ms-2" title="{{ __('Pending Module Verifications') }}">
                                                <i class="fas fa-exclamation-triangle"></i> {{ count($pendingModules) }}
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($user->type) }}</span>
                                    </td>
                                    <td>
                                        @if($user->verification_status === 'pending')
                                            <span class="badge bg-secondary">{{ __('Pending') }}</span>
                                        @elseif($user->verification_status === 'under_review')
                                            <span class="badge bg-warning">{{ __('Under Review') }}</span>
                                        @elseif($user->verification_status === 'approved')
                                            <span class="badge bg-success">{{ __('Approved') }}</span>
                                        @elseif($user->verification_status === 'rejected')
                                            <span class="badge bg-danger">{{ __('Rejected') }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small>{{ $approvedCount }}/{{ $docCount }} {{ __('approved') }}</small>
                                        @if(!empty($pendingModules))
                                            <br><small class="text-warning">
                                                <i class="fas fa-clock"></i> {{ __('Modules pending:') }} {{ implode(', ', $pendingModules) }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar" role="progressbar" style="width: {{ $progress }}%">
                                                {{ $progress }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('dashboard.verifications.show', $user->id) }}" 
                                           class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> {{ __('Review') }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">{{ __('No pending verifications') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($users->hasPages())
                    <div class="card-footer">
                        <div style="padding:5px;direction: ltr;">
                            {!! $users->withQueryString()->links('pagination::bootstrap-5') !!}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</main>
@endsection

