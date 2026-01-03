@extends('web.layouts.dashboard_master')

@section('title', __('Invoice Details'))
@section('header_title', __('Invoice Details'))

@section('content')
@if(!$clinic)
<div class="alert alert-warning">
    <i class="las la-exclamation-triangle"></i> {{ __('Please set up your clinic first.') }}
</div>
@else
<div class="row">
    <div class="col-lg-10 mx-auto">
        <!-- Header Actions -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="font-weight-bold mb-0">{{ __('Invoice Details') }}</h4>
                <p class="text-muted mb-0">{{ __('Invoice Number') }}: <strong>{{ $invoice->invoice_number }}</strong></p>
            </div>
            <div>
                <a href="{{ route('clinic.invoices.index') }}" class="btn btn-secondary">
                    <i class="las la-arrow-left"></i> {{ __('Back to List') }}
                </a>
                @if($invoice->status != 'paid')
                <a href="{{ route('clinic.invoices.edit', $invoice) }}" class="btn btn-primary">
                    <i class="las la-edit"></i> {{ __('Edit') }}
                </a>
                @endif
                <a href="{{ route('clinic.payments.create') }}?patient_id={{ $invoice->patient_id }}&invoice_id={{ $invoice->id }}" class="btn btn-success">
                    <i class="las la-plus-circle"></i> {{ __('Record Payment') }}
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Left Column: Details -->
            <div class="col-md-8">
                <!-- Info Card -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                        <h5 class="font-weight-bold mb-0 text-primary">{{ __('Invoice Summary') }}</h5>
                        @php
                            $statusClass = [
                                'paid' => 'success',
                                'partially_paid' => 'warning',
                                'unpaid' => 'danger'
                            ];
                            $status = $statusClass[$invoice->status] ?? 'secondary';
                        @endphp
                        <span class="badge bg-{{ $status }} px-3 py-2" style="font-size: 0.9rem;">
                            {{ ucfirst(str_replace('_', ' ', $invoice->status)) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="text-muted small text-uppercase fw-bold">{{ __('Invoice Date') }}</label>
                                <p class="mb-0">{{ $invoice->invoice_date->format('F d, Y') }}</p>
                            </div>
                            <div class="col-md-6">
                                <label class="text-muted small text-uppercase fw-bold">{{ __('Due Date') }}</label>
                                <p class="mb-0">{{ $invoice->due_date ? $invoice->due_date->format('F d, Y') : '-' }}</p>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="text-muted small text-uppercase fw-bold">{{ __('Treatment Plan / Service') }}</label>
                            <p class="mb-0 font-weight-bold">{{ $invoice->treatment_plan ?? '-' }}</p>
                        </div>

                        @if($invoice->notes)
                        <div class="mb-4">
                            <label class="text-muted small text-uppercase fw-bold">{{ __('Notes') }}</label>
                            <p class="mb-0 text-muted">{{ $invoice->notes }}</p>
                        </div>
                        @endif

                        <hr>

                        <div class="row mt-4">
                            <div class="col-md-4 text-center">
                                <label class="text-muted small text-uppercase fw-bold">{{ __('Subtotal') }}</label>
                                <h5 class="mb-0">{{ number_format($invoice->total_cost, 2) }} {{ __('EGP') }}</h5>
                            </div>
                            <div class="col-md-4 text-center">
                                <label class="text-muted small text-uppercase fw-bold">{{ __('Discount') }}</label>
                                <h5 class="mb-0 text-danger">-{{ number_format($invoice->discount, 2) }} {{ __('EGP') }}</h5>
                            </div>
                            <div class="col-md-4 text-center bg-light py-2 rounded">
                                <label class="text-muted small text-uppercase fw-bold">{{ __('Final Amount') }}</label>
                                <h4 class="mb-0 font-weight-bold">{{ number_format($invoice->final_amount, 2) }} {{ __('EGP') }}</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payments Card -->
                <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="font-weight-bold mb-0">{{ __('Payments History') }}</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="border-0">{{ __('Payment #') }}</th>
                                        <th class="border-0">{{ __('Date') }}</th>
                                        <th class="border-0">{{ __('Method') }}</th>
                                        <th class="border-0 text-end">{{ __('Amount') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($invoice->payments as $payment)
                                    <tr>
                                        <td>
                                            <a href="{{ route('clinic.payments.show', $payment) }}" class="fw-bold">
                                                {{ $payment->payment_number }}
                                            </a>
                                        </td>
                                        <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</td>
                                        <td class="text-end font-weight-bold">{{ number_format($payment->payment_amount, 2) }} {{ __('EGP') }}</td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">
                                            {{ __('No payments recorded yet.') }}
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                                @if($invoice->payments->count() > 0)
                                <tfoot class="bg-light fw-bold">
                                    <tr>
                                        <td colspan="3" class="text-end">{{ __('Total Paid') }}</td>
                                        <td class="text-end text-success">{{ number_format($invoice->total_paid, 2) }} {{ __('EGP') }}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="text-end">{{ __('Remaining Balance') }}</td>
                                        <td class="text-end text-danger">{{ number_format($invoice->remaining_balance, 2) }} {{ __('EGP') }}</td>
                                    </tr>
                                </tfoot>
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column: Patient Info -->
            <div class="col-md-4">
                <div class="card border-0 shadow-sm sticky-top" style="border-radius: 15px; top: 20px;">
                    <div class="card-header bg-white border-0 py-3">
                        <h5 class="font-weight-bold mb-0 text-primary">{{ __('Patient Details') }}</h5>
                    </div>
                    <div class="card-body text-center">
                        <div class="avatar-lg rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px;">
                            <i class="las la-user fa-3x text-primary"></i>
                        </div>
                        <h5 class="font-weight-bold mb-1">{{ $invoice->patient->full_name ?? '-' }}</h5>
                        <p class="text-muted mb-3">{{ $invoice->patient->phone ?? '-' }}</p>
                        
                        <div class="text-start mt-4">
                            <div class="mb-3">
                                <label class="text-muted small text-uppercase fw-bold">{{ __('Email') }}</label>
                                <p class="mb-0">{{ $invoice->patient->email ?? '-' }}</p>
                            </div>
                            <div class="mb-3">
                                <label class="text-muted small text-uppercase fw-bold">{{ __('Address') }}</label>
                                <p class="mb-0">{{ $invoice->patient->address ?? '-' }}</p>
                            </div>
                        </div>

                        <hr>
                        
                        <a href="{{ route('clinic.patients.show', $invoice->patient) }}" class="btn btn-outline-primary btn-block">
                            <i class="las la-user-circle"></i> {{ __('View Full Profile') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
