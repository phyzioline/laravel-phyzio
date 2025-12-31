@extends('web.layouts.dashboard_master')

@section('title', __('Patient Invoices'))
@section('header_title', __('Patient Invoices & Billing'))

@section('content')
@if(!$clinic)
<div class="alert alert-warning">
    <i class="las la-exclamation-triangle"></i> {{ __('Please set up your clinic first.') }}
</div>
@else
<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm bg-primary text-white" style="border-radius: 15px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-white bg-opacity-20 rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="las la-file-invoice-dollar fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">{{ __('Total Invoiced') }}</h6>
                        <h3 class="mb-0">{{ number_format($stats['total_invoiced'] ?? 0, 2) }} {{ __('EGP') }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-success text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="las la-check-circle fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 text-muted">{{ __('Total Paid') }}</h6>
                        <h3 class="mb-0 text-success">{{ number_format($stats['total_paid'] ?? 0, 2) }} {{ __('EGP') }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-warning text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="las la-exclamation-triangle fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 text-muted">{{ __('Outstanding') }}</h6>
                        <h3 class="mb-0 text-warning">{{ number_format($stats['outstanding'] ?? 0, 2) }} {{ __('EGP') }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-info text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="las la-clipboard-list fa-lg"></i>
                    </div>
                    <div>
                        <h6 class="mb-1 text-muted">{{ __('Total Invoices') }}</h6>
                        <h3 class="mb-0 text-info">{{ array_sum($stats['by_status'] ?? []) }}</h3>
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
            <h4 class="font-weight-bold">{{ __('Invoices List') }}</h4>
            <a href="{{ route('clinic.invoices.create') }}" class="btn btn-primary">
                <i class="las la-plus"></i> {{ __('Create Invoice') }}
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
    <div id="filtersCollapse" class="collapse {{ request()->hasAny(['patient_id', 'status', 'date_from', 'date_to']) ? 'show' : '' }}">
        <div class="card-body">
            <form method="GET" action="{{ route('clinic.invoices.index') }}" class="row g-3">
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
                    <label class="form-label">{{ __('Status') }}</label>
                    <select name="status" class="form-select">
                        <option value="">{{ __('All Statuses') }}</option>
                        <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>{{ __('Unpaid') }}</option>
                        <option value="partially_paid" {{ request('status') == 'partially_paid' ? 'selected' : '' }}>{{ __('Partially Paid') }}</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>{{ __('Paid') }}</option>
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
                    <a href="{{ route('clinic.invoices.index') }}" class="btn btn-secondary">
                        <i class="las la-times"></i> {{ __('Clear') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Invoices Table -->
<div class="card border-0 shadow-sm" style="border-radius: 15px;">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>{{ __('Invoice #') }}</th>
                        <th>{{ __('Patient') }}</th>
                        <th>{{ __('Treatment Plan') }}</th>
                        <th>{{ __('Total Cost') }}</th>
                        <th>{{ __('Discount') }}</th>
                        <th>{{ __('Final Amount') }}</th>
                        <th>{{ __('Paid') }}</th>
                        <th>{{ __('Balance') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($invoices as $invoice)
                    <tr>
                        <td><strong>{{ $invoice->invoice_number }}</strong></td>
                        <td>{{ $invoice->patient->full_name ?? '-' }}</td>
                        <td>{{ Str::limit($invoice->treatment_plan ?? '-', 30) }}</td>
                        <td>{{ number_format($invoice->total_cost, 2) }} {{ __('EGP') }}</td>
                        <td>{{ number_format($invoice->discount, 2) }} {{ __('EGP') }}</td>
                        <td><strong>{{ number_format($invoice->final_amount, 2) }} {{ __('EGP') }}</strong></td>
                        <td class="text-success">{{ number_format($invoice->total_paid, 2) }} {{ __('EGP') }}</td>
                        <td class="text-warning"><strong>{{ number_format($invoice->remaining_balance, 2) }} {{ __('EGP') }}</strong></td>
                        <td>
                            @if($invoice->status == 'paid')
                                <span class="badge bg-success">{{ __('Paid') }}</span>
                            @elseif($invoice->status == 'partially_paid')
                                <span class="badge bg-warning">{{ __('Partially Paid') }}</span>
                            @else
                                <span class="badge bg-danger">{{ __('Unpaid') }}</span>
                            @endif
                        </td>
                        <td>{{ $invoice->invoice_date->format('Y-m-d') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('clinic.invoices.show', $invoice) }}" class="btn btn-info" title="{{ __('View') }}">
                                    <i class="las la-eye"></i>
                                </a>
                                @if($invoice->status != 'paid')
                                <a href="{{ route('clinic.invoices.edit', $invoice) }}" class="btn btn-warning" title="{{ __('Edit') }}">
                                    <i class="las la-edit"></i>
                                </a>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="11" class="text-center py-4">
                            <div class="alert alert-info mb-0">
                                <i class="las la-info-circle"></i> {{ __('No invoices found.') }}
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($invoices->hasPages())
        <div class="mt-4">
            {{ $invoices->links() }}
        </div>
        @endif
    </div>
</div>
@endif
@endsection

