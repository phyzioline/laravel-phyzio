@extends('web.layouts.dashboard_master')

@section('title', 'Waitlist')
@section('header_title', 'Patient Waitlist')

@section('content')
@if(!$clinic)
<div class="alert alert-warning">
    <i class="las la-exclamation-triangle"></i> {{ __('Please set up your clinic first.') }}
</div>
@else
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body">
                <h6 class="text-muted mb-2">{{ __('Total') }}</h6>
                <h3 class="mb-0">{{ $stats['total'] ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body">
                <h6 class="text-muted mb-2">{{ __('Active') }}</h6>
                <h3 class="mb-0 text-primary">{{ $stats['active'] ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body">
                <h6 class="text-muted mb-2">{{ __('Urgent') }}</h6>
                <h3 class="mb-0 text-danger">{{ $stats['urgent'] ?? 0 }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body">
                <h6 class="text-muted mb-2">{{ __('Notified') }}</h6>
                <h3 class="mb-0 text-warning">{{ $stats['notified'] ?? 0 }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
    <div class="card-body">
        <form method="GET" action="{{ route('clinic.waitlist.index') }}" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">{{ __('Patient') }}</label>
                <select name="patient_id" class="form-control">
                    <option value="">{{ __('All Patients') }}</option>
                    @foreach($patients ?? [] as $patient)
                        <option value="{{ $patient->id }}" {{ request('patient_id') == $patient->id ? 'selected' : '' }}>
                            {{ $patient->first_name }} {{ $patient->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">{{ __('Status') }}</label>
                <select name="status" class="form-control">
                    <option value="">{{ __('All') }}</option>
                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                    <option value="notified" {{ request('status') == 'notified' ? 'selected' : '' }}>{{ __('Notified') }}</option>
                    <option value="booked" {{ request('status') == 'booked' ? 'selected' : '' }}>{{ __('Booked') }}</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">{{ __('Priority') }}</label>
                <select name="priority" class="form-control">
                    <option value="">{{ __('All') }}</option>
                    <option value="urgent" {{ request('priority') == 'urgent' ? 'selected' : '' }}>{{ __('Urgent') }}</option>
                    <option value="high" {{ request('priority') == 'high' ? 'selected' : '' }}>{{ __('High') }}</option>
                    <option value="normal" {{ request('priority') == 'normal' ? 'selected' : '' }}>{{ __('Normal') }}</option>
                    <option value="low" {{ request('priority') == 'low' ? 'selected' : '' }}>{{ __('Low') }}</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary mr-2"><i class="las la-filter"></i> {{ __('Filter') }}</button>
                <a href="{{ route('clinic.waitlist.index') }}" class="btn btn-outline-secondary">{{ __('Clear') }}</a>
            </div>
        </form>
    </div>
</div>

<!-- Waitlist Table -->
<div class="card border-0 shadow-sm" style="border-radius: 15px;">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h5 class="font-weight-bold mb-0">{{ __('Waitlist') }}</h5>
        <a href="{{ route('clinic.waitlist.create') }}" class="btn btn-primary">
            <i class="las la-plus"></i> {{ __('Add to Waitlist') }}
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>{{ __('Position') }}</th>
                        <th>{{ __('Patient') }}</th>
                        <th>{{ __('Doctor') }}</th>
                        <th>{{ __('Specialty') }}</th>
                        <th>{{ __('Priority') }}</th>
                        <th>{{ __('Preferred Dates') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Added') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($waitlist ?? [] as $index => $entry)
                    <tr>
                        <td>
                            <span class="badge badge-secondary">{{ $waitlist->firstItem() + $index }}</span>
                        </td>
                        <td>
                            <a href="{{ route('clinic.patients.show', $entry->patient_id) }}" class="text-primary">
                                {{ $entry->patient->first_name ?? '' }} {{ $entry->patient->last_name ?? '' }}
                            </a>
                        </td>
                        <td>
                            @if($entry->doctor)
                                {{ $entry->doctor->name ?? 'N/A' }}
                            @else
                                <span class="text-muted">{{ __('Any') }}</span>
                            @endif
                        </td>
                        <td>{{ $entry->specialty ?? '-' }}</td>
                        <td>
                            @php
                                $priorityColors = [
                                    'urgent' => 'danger',
                                    'high' => 'warning',
                                    'normal' => 'info',
                                    'low' => 'secondary'
                                ];
                            @endphp
                            <span class="badge badge-{{ $priorityColors[$entry->priority] ?? 'secondary' }}">
                                {{ ucfirst($entry->priority) }}
                            </span>
                        </td>
                        <td>
                            @if($entry->preferred_start_date)
                                {{ \Carbon\Carbon::parse($entry->preferred_start_date)->format('M d') }}
                                @if($entry->preferred_end_date)
                                    - {{ \Carbon\Carbon::parse($entry->preferred_end_date)->format('M d') }}
                                @endif
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusColors = [
                                    'active' => 'primary',
                                    'notified' => 'warning',
                                    'booked' => 'success',
                                    'cancelled' => 'secondary'
                                ];
                            @endphp
                            <span class="badge badge-{{ $statusColors[$entry->status] ?? 'secondary' }}">
                                {{ ucfirst($entry->status) }}
                            </span>
                        </td>
                        <td>
                            <small class="text-muted">{{ $entry->created_at->format('M d, Y') }}</small>
                        </td>
                        <td>
                            @if($entry->status === 'active')
                                <button class="btn btn-sm btn-outline-danger" onclick="removeFromWaitlist({{ $entry->id }})" title="{{ __('Remove') }}">
                                    <i class="las la-times"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <i class="las la-list fa-3x text-muted mb-3"></i>
                            <p class="text-muted">{{ __('No patients on waitlist') }}</p>
                            <a href="{{ route('clinic.waitlist.create') }}" class="btn btn-primary">
                                <i class="las la-plus"></i> {{ __('Add First Patient') }}
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(isset($waitlist) && $waitlist->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $waitlist->links() }}
        </div>
        @endif
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
function removeFromWaitlist(id) {
    if (!confirm('Are you sure you want to remove this patient from the waitlist?')) {
        return;
    }

    fetch(`/clinic/waitlist/${id}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Error: ' + (data.message || 'Failed to remove from waitlist'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to remove from waitlist');
    });
}
</script>
@endpush

