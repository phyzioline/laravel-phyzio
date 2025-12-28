@extends('web.layouts.dashboard_master')

@section('title', 'Insurance Claims')
@section('header_title', 'Insurance Claims (RCM)')

@section('content')
@if(!$clinic)
<div class="alert alert-warning">
    <i class="las la-exclamation-triangle"></i> {{ __('Please set up your clinic first.') }}
</div>
@else
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center" style="border-radius: 15px;">
            <div class="card-body">
                <h3 class="mb-0">{{ $stats['total'] ?? 0 }}</h3>
                <small class="text-muted">{{ __('Total Claims') }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center" style="border-radius: 15px;">
            <div class="card-body">
                <h3 class="mb-0 text-warning">{{ $stats['pending'] ?? 0 }}</h3>
                <small class="text-muted">{{ __('Pending') }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center" style="border-radius: 15px;">
            <div class="card-body">
                <h3 class="mb-0 text-info">{{ $stats['submitted'] ?? 0 }}</h3>
                <small class="text-muted">{{ __('Submitted') }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center" style="border-radius: 15px;">
            <div class="card-body">
                <h3 class="mb-0 text-success">{{ $stats['paid'] ?? 0 }}</h3>
                <small class="text-muted">{{ __('Paid') }}</small>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
    <div class="card-body">
        <form method="GET" action="{{ route('clinic.insurance-claims.index') }}" class="row">
            <div class="col-md-3">
                <select name="status" class="form-control">
                    <option value="">{{ __('All Statuses') }}</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>{{ __('Draft') }}</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('Pending') }}</option>
                    <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>{{ __('Submitted') }}</option>
                    <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>{{ __('Paid') }}</option>
                    <option value="denied" {{ request('status') == 'denied' ? 'selected' : '' }}>{{ __('Denied') }}</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}" placeholder="{{ __('From Date') }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}" placeholder="{{ __('To Date') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary btn-block">{{ __('Filter') }}</button>
            </div>
        </form>
    </div>
</div>

<!-- Claims Table -->
<div class="card border-0 shadow-sm" style="border-radius: 15px;">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h5 class="font-weight-bold mb-0">{{ __('Insurance Claims') }}</h5>
        <button type="button" class="btn btn-sm btn-primary" onclick="batchSubmit()">
            <i class="las la-paper-plane"></i> {{ __('Batch Submit') }}
        </button>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th><input type="checkbox" id="selectAll"></th>
                        <th>{{ __('Claim #') }}</th>
                        <th>{{ __('Patient') }}</th>
                        <th>{{ __('Date of Service') }}</th>
                        <th>{{ __('Billed Amount') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($claims ?? [] as $claim)
                    <tr>
                        <td>
                            <input type="checkbox" class="claim-checkbox" value="{{ $claim->id }}">
                        </td>
                        <td>
                            <strong>{{ $claim->claim_number }}</strong>
                        </td>
                        <td>
                            {{ $claim->patient->first_name ?? '' }} {{ $claim->patient->last_name ?? '' }}
                        </td>
                        <td>
                            {{ $claim->date_of_service->format('M d, Y') }}
                        </td>
                        <td>
                            ${{ number_format($claim->billed_amount, 2) }}
                        </td>
                        <td>
                            @php
                                $statusColors = [
                                    'draft' => 'secondary',
                                    'pending' => 'warning',
                                    'submitted' => 'info',
                                    'paid' => 'success',
                                    'denied' => 'danger',
                                    'partial' => 'primary',
                                ];
                                $color = $statusColors[$claim->status] ?? 'secondary';
                            @endphp
                            <span class="badge badge-{{ $color }}">{{ ucfirst($claim->status) }}</span>
                        </td>
                        <td>
                            <a href="{{ route('clinic.insurance-claims.show', $claim->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="las la-eye"></i>
                            </a>
                            @if($claim->status === 'draft' || $claim->status === 'pending')
                            <button type="button" class="btn btn-sm btn-outline-success" onclick="submitClaim({{ $claim->id }})">
                                <i class="las la-paper-plane"></i>
                            </button>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="las la-file-invoice-dollar fa-3x text-muted mb-3"></i>
                            <p class="text-muted">{{ __('No insurance claims found') }}</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(isset($claims) && $claims->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $claims->links() }}
        </div>
        @endif
    </div>
</div>
@endif
@endsection

@push('scripts')
<script>
function submitClaim(claimId) {
    if (confirm('{{ __('Are you sure you want to submit this claim?') }}')) {
        fetch(`{{ route('clinic.insurance-claims.index') }}/${claimId}/submit`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success || response.ok) {
                location.reload();
            } else {
                alert(data.message || '{{ __('Failed to submit claim') }}');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            location.reload(); // Reload to show updated status
        });
    }
}

function batchSubmit() {
    const selected = Array.from(document.querySelectorAll('.claim-checkbox:checked')).map(cb => cb.value);
    
    if (selected.length === 0) {
        alert('{{ __('Please select at least one claim') }}');
        return;
    }
    
    if (confirm(`{{ __('Submit') }} ${selected.length} {{ __('claims?') }}`)) {
        fetch('{{ route('clinic.insurance-claims.batchSubmit') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ claim_ids: selected }),
        })
        .then(response => response.json())
        .then(data => {
            alert(data.message || '{{ __('Claims submitted') }}');
            location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('{{ __('Failed to submit claims') }}');
        });
    }
}

document.getElementById('selectAll').addEventListener('change', function() {
    document.querySelectorAll('.claim-checkbox').forEach(cb => {
        cb.checked = this.checked;
    });
});
</script>
@endpush

