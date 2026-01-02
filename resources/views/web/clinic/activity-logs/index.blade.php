@extends('web.layouts.dashboard_master')

@section('title', __('Activity Logs'))
@section('header_title', __('Activity Logs'))

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="font-weight-bold">{{ __('Activity Logs') }}</h4>
            <div>
                <span class="badge badge-info">{{ __('Admin Only') }}</span>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
    <div class="card-body">
        <form method="GET" action="{{ route('clinic.activity-logs.index') }}" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">{{ __('User') }}</label>
                <select name="user_id" class="form-control">
                    <option value="">{{ __('All Users') }}</option>
                    @foreach($users ?? [] as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">{{ __('Action') }}</label>
                <select name="action" class="form-control">
                    <option value="">{{ __('All Actions') }}</option>
                    <option value="created" {{ request('action') == 'created' ? 'selected' : '' }}>{{ __('Created') }}</option>
                    <option value="updated" {{ request('action') == 'updated' ? 'selected' : '' }}>{{ __('Updated') }}</option>
                    <option value="deleted" {{ request('action') == 'deleted' ? 'selected' : '' }}>{{ __('Deleted') }}</option>
                    <option value="viewed" {{ request('action') == 'viewed' ? 'selected' : '' }}>{{ __('Viewed') }}</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">{{ __('Date From') }}</label>
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">{{ __('Date To') }}</label>
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div>
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="las la-search"></i> {{ __('Filter') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Activity Logs Table -->
<div class="card border-0 shadow-sm" style="border-radius: 15px;">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>{{ __('Date & Time') }}</th>
                        <th>{{ __('User') }}</th>
                        <th>{{ __('Action') }}</th>
                        <th>{{ __('Model') }}</th>
                        <th>{{ __('Description') }}</th>
                        <th>{{ __('IP Address') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td>
                            <div>{{ $log->created_at->format('M d, Y') }}</div>
                            <small class="text-muted">{{ $log->created_at->format('h:i A') }}</small>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary text-white rounded-circle mr-2 d-flex align-items-center justify-content-center" 
                                     style="width: 30px; height: 30px; font-size: 12px;">
                                    {{ substr($log->user->name ?? 'U', 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-weight-bold">{{ $log->user->name ?? 'Unknown' }}</div>
                                    <small class="text-muted">{{ $log->user->email ?? '' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="badge badge-{{ $log->action == 'created' ? 'success' : ($log->action == 'updated' ? 'warning' : ($log->action == 'deleted' ? 'danger' : 'info')) }}">
                                {{ ucfirst($log->action) }}
                            </span>
                        </td>
                        <td>
                            <code>{{ class_basename($log->model_type) }}</code>
                            @if($log->model_id)
                                <br><small class="text-muted">ID: {{ $log->model_id }}</small>
                            @endif
                        </td>
                        <td>
                            <div>{{ Str::limit($log->description ?? $log->action_description, 50) }}</div>
                            @if($log->route)
                                <small class="text-muted">{{ $log->route }}</small>
                            @endif
                        </td>
                        <td>
                            <small class="text-muted">{{ $log->ip_address ?? '-' }}</small>
                        </td>
                        <td>
                            <a href="{{ route('clinic.activity-logs.show', $log->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="las la-eye"></i> {{ __('View') }}
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <i class="las la-inbox fa-3x text-muted mb-3"></i>
                            <p class="text-muted">{{ __('No activity logs found') }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($logs->hasPages())
        <div class="mt-4">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

