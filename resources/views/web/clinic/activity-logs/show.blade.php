@extends('web.layouts.dashboard_master')

@section('title', __('Activity Log Details'))
@section('header_title', __('Activity Log Details'))

@section('content')
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title font-weight-bold mb-0">{{ __('Activity Log Details') }}</h5>
                    <a href="{{ route('clinic.activity-logs.index') }}" class="btn btn-secondary">
                        <i class="las la-arrow-left"></i> {{ __('Back') }}
                    </a>
                </div>
            </div>
            <div class="card-body px-4">
                <!-- Basic Info -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">{{ __('User') }}</h6>
                        <div class="d-flex align-items-center">
                            <div class="bg-primary text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" 
                                 style="width: 50px; height: 50px;">
                                {{ substr($log->user->name ?? 'U', 0, 1) }}
                            </div>
                            <div>
                                <div class="font-weight-bold">{{ $log->user->name ?? 'Unknown' }}</div>
                                <small class="text-muted">{{ $log->user->email ?? '' }}</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-muted mb-2">{{ __('Date & Time') }}</h6>
                        <div class="font-weight-bold">{{ $log->created_at->format('F d, Y h:i A') }}</div>
                        <small class="text-muted">{{ $log->created_at->diffForHumans() }}</small>
                    </div>
                </div>

                <hr>

                <!-- Action Details -->
                <div class="mb-4">
                    <h6 class="text-muted mb-2">{{ __('Action') }}</h6>
                    <span class="badge badge-lg badge-{{ $log->action == 'created' ? 'success' : ($log->action == 'updated' ? 'warning' : ($log->action == 'deleted' ? 'danger' : 'info')) }}">
                        {{ ucfirst($log->action) }}
                    </span>
                </div>

                <div class="mb-4">
                    <h6 class="text-muted mb-2">{{ __('Model') }}</h6>
                    <code>{{ $log->model_type }}</code>
                    @if($log->model_id)
                        <span class="text-muted">(ID: {{ $log->model_id }})</span>
                    @endif
                </div>

                @if($log->description)
                <div class="mb-4">
                    <h6 class="text-muted mb-2">{{ __('Description') }}</h6>
                    <p>{{ $log->description }}</p>
                </div>
                @endif

                <!-- Changes Comparison -->
                @if($log->old_values || $log->new_values)
                <hr>
                <h6 class="font-weight-bold mb-3">{{ __('Changes Made') }}</h6>
                <div class="row">
                    @if($log->old_values)
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-header bg-danger text-white">
                                <strong>{{ __('Old Values') }}</strong>
                            </div>
                            <div class="card-body">
                                <pre class="mb-0" style="font-size: 12px;">{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                    </div>
                    @endif
                    @if($log->new_values)
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-header bg-success text-white">
                                <strong>{{ __('New Values') }}</strong>
                            </div>
                            <div class="card-body">
                                <pre class="mb-0" style="font-size: 12px;">{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                <!-- Technical Details -->
                <hr>
                <h6 class="font-weight-bold mb-3">{{ __('Technical Details') }}</h6>
                <div class="row">
                    <div class="col-md-6 mb-2">
                        <strong>{{ __('IP Address') }}:</strong> {{ $log->ip_address ?? '-' }}
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong>{{ __('Route') }}:</strong> <code>{{ $log->route ?? '-' }}</code>
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong>{{ __('HTTP Method') }}:</strong> <span class="badge badge-info">{{ $log->method ?? '-' }}</span>
                    </div>
                    <div class="col-md-6 mb-2">
                        <strong>{{ __('User Agent') }}:</strong> 
                        <small class="text-muted">{{ Str::limit($log->user_agent ?? '-', 50) }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

