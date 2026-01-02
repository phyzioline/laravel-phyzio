@extends('web.layouts.dashboard_master')

@section('title', __('Patient Payments'))
@section('header_title', __('Patient Payments Management'))

@section('content')
@if(!$clinic)
<div class="alert alert-warning">
    <i class="las la-exclamation-triangle"></i> {{ __('Please set up your clinic first.') }}
</div>
@else
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm bg-success text-white" style="border-radius: 15px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-20 rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="las la-money-check-alt fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">{{ __('Total Paid') }}</h6>
                        <h3 class="mb-0">{{ number_format($stats['total_paid'] ?? 0, 2) }} {{ __('EGP') }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-primary text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="las la-calendar fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 text-muted">{{ __('This Month') }}</h6>
                        <h3 class="mb-0 text-primary">{{ number_format($stats['this_month'] ?? 0, 2) }} {{ __('EGP') }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Actions Bar -->
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <h4 class="font-weight-bold">{{ __('Payments List') }}</h4>
            <a href="{{ route('clinic.payments.create') }}" class="btn btn-primary">
                <i class="las la-plus"></i> {{ __('Record Payment') }}
            </a>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
    <div class="card-header bg-white border-0">
        <h5 class="mb-0">
            <button class="btn btn-link text-decoration-none" type="button" data-bs-toggle="collapse" data-bs-target="#filtersCollapse">
                <i class="las la-filter"></i> {{ __('Filters') }}
            </button>
        </h5>
    </div>
    <div id="filtersCollapse" class="collapse {{ request()->hasAny(['patient_id', 'invoice_id', 'date_from', 'date_to', 'payment_method']) ? 'show' : '' }}">
        <div class="card-body">
            <form method="GET" action="{{ route('clinic.payments.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">{{ __('Patient') }}</label>
                    <select name="patient_id" class="form-select">
                        <option value="">{{ __('All Patients') }}</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ request('patient_id') == $patient->id ? 'selected' : '' }}>
                                {{ $patient->full_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('Payment Method') }}</label>
                    <select name="payment_method" class="form-select">
                        <option value="">{{ __('All Methods') }}</option>
                        <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>{{ __('Cash') }}</option>
                        <option value="bank_transfer" {{ request('payment_method') == 'bank_transfer' ? 'selected' : '' }}>{{ __('Bank Transfer') }}</option>
                        <option value="pos_card" {{ request('payment_method') == 'pos_card' ? 'selected' : '' }}>{{ __('POS / Card') }}</option>
                        <option value="mobile_wallet" {{ request('payment_method') == 'mobile_wallet' ? 'selected' : '' }}>{{ __('Mobile Wallet') }}</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('Date From') }}</label>
                    <input type="date" name="date_from" class="form-control" value="{{ request('date_from') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label">{{ __('Date To') }}</label>
                    <input type="date" name="date_to" class="form-control" value="{{ request('date_to') }}">
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">
                        <i class="las la-search"></i> {{ __('Apply Filters') }}
                    </button>
                    <a href="{{ route('clinic.payments.index') }}" class="btn btn-secondary">
                        <i class="las la-times"></i> {{ __('Clear') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Payments Table -->
<div class="card border-0 shadow-sm" style="border-radius: 15px;">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>{{ __('Payment #') }}</th>
                        <th>{{ __('Patient') }}</th>
                        <th>{{ __('Invoice') }}</th>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Amount') }}</th>
                        <th>{{ __('Method') }}</th>
                        <th>{{ __('Received By') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payments as $payment)
                    <tr>
                        <td data-label="{{ __('Payment #') }}"><strong>{{ $payment->payment_number }}</strong></td>
                        <td data-label="{{ __('Patient') }}">{{ $payment->patient->full_name ?? '-' }}</td>
                        <td data-label="{{ __('Invoice') }}">
                            @if($payment->invoice)
                                <a href="{{ route('clinic.invoices.show', $payment->invoice) }}">
                                    {{ $payment->invoice->invoice_number }}
                                </a>
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td data-label="{{ __('Date') }}">{{ $payment->payment_date->format('Y-m-d') }}</td>
                        <td data-label="{{ __('Amount') }}"><strong class="text-success">{{ number_format($payment->payment_amount, 2) }} {{ __('EGP') }}</strong></td>
                        <td data-label="{{ __('Method') }}"><span class="badge bg-secondary">{{ $payment->payment_method_name }}</span></td>
                        <td data-label="{{ __('Received By') }}">{{ $payment->receivedBy->name ?? '-' }}</td>
                        <td data-label="{{ __('Actions') }}">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('clinic.payments.show', $payment) }}" class="btn btn-info" title="{{ __('View') }}">
                                    <i class="las la-eye"></i>
                                </a>
                                <a href="{{ route('clinic.payments.edit', $payment) }}" class="btn btn-warning" title="{{ __('Edit') }}">
                                    <i class="las la-edit"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <div class="alert alert-info mb-0">
                                <i class="las la-info-circle"></i> {{ __('No payments found.') }}
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($payments->hasPages())
        <div class="mt-4">
            {{ $payments->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Mobile Sticky Action Button -->
<div class="mobile-action-button d-md-none">
    <a href="{{ route('clinic.payments.create') }}" class="btn btn-primary btn-lg rounded-circle" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
        <i class="las la-plus fa-2x"></i>
    </a>
</div>
@endif

@push('styles')
<style>
    /* Additional mobile optimizations for payments page */
    @media (max-width: 768px) {
        .table td[data-label]:before {
            content: attr(data-label);
            font-weight: 600;
            color: #6c757d;
            margin-right: 12px;
        }
        
        .btn-group {
            width: 100%;
            display: flex;
        }
        
        .btn-group .btn {
            flex: 1;
        }
    }
</style>
@endpush
@endsection

