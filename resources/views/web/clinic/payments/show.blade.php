@extends('web.layouts.dashboard_master')

@section('title', __('Payment Details'))
@section('header_title', __('Payment Details'))

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
                <h4 class="font-weight-bold mb-0">{{ __('Payment Details') }}</h4>
                <p class="text-muted mb-0">{{ __('Payment Number') }}: <strong>{{ $payment->payment_number }}</strong></p>
            </div>
            <div>
                <a href="{{ route('clinic.payments.index') }}" class="btn btn-secondary">
                    <i class="las la-arrow-left"></i> {{ __('Back to List') }}
                </a>
                <a href="{{ route('clinic.payments.edit', $payment) }}" class="btn btn-primary">
                    <i class="las la-edit"></i> {{ __('Edit') }}
                </a>
            </div>
        </div>

        <!-- Payment Information Card -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="font-weight-bold mb-0">{{ __('Payment Information') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">{{ __('Payment Number') }}</label>
                        <p class="mb-0 font-weight-bold">{{ $payment->payment_number }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">{{ __('Payment Date') }}</label>
                        <p class="mb-0">{{ $payment->payment_date->format('F d, Y') }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">{{ __('Payment Amount') }}</label>
                        <p class="mb-0">
                            <span class="h4 text-success font-weight-bold">{{ number_format($payment->payment_amount, 2) }} {{ __('EGP') }}</span>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">{{ __('Payment Method') }}</label>
                        <p class="mb-0">
                            <span class="badge bg-secondary">{{ $payment->payment_method_name }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Patient Information Card -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="font-weight-bold mb-0">{{ __('Patient Information') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">{{ __('Patient Name') }}</label>
                        <p class="mb-0">
                            <a href="{{ route('clinic.patients.show', $payment->patient) }}" class="text-decoration-none">
                                <strong>{{ $payment->patient->full_name ?? '-' }}</strong>
                            </a>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">{{ __('Phone') }}</label>
                        <p class="mb-0">{{ $payment->patient->phone ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">{{ __('Email') }}</label>
                        <p class="mb-0">{{ $payment->patient->email ?? '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Invoice Information Card (if linked) -->
        @if($payment->invoice)
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="font-weight-bold mb-0">{{ __('Linked Invoice') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">{{ __('Invoice Number') }}</label>
                        <p class="mb-0">
                            <a href="{{ route('clinic.invoices.show', $payment->invoice) }}" class="text-decoration-none">
                                <strong>{{ $payment->invoice->invoice_number }}</strong>
                            </a>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">{{ __('Invoice Amount') }}</label>
                        <p class="mb-0">{{ number_format($payment->invoice->final_amount, 2) }} {{ __('EGP') }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">{{ __('Invoice Status') }}</label>
                        <p class="mb-0">
                            @php
                                $statusClass = [
                                    'paid' => 'success',
                                    'partially_paid' => 'warning',
                                    'unpaid' => 'danger'
                                ];
                                $status = $statusClass[$payment->invoice->status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $status }}">{{ ucfirst(str_replace('_', ' ', $payment->invoice->status)) }}</span>
                        </p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">{{ __('Remaining Balance') }}</label>
                        <p class="mb-0">
                            <strong class="{{ $payment->invoice->remaining_balance > 0 ? 'text-warning' : 'text-success' }}">
                                {{ number_format($payment->invoice->remaining_balance, 2) }} {{ __('EGP') }}
                            </strong>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Additional Information Card -->
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="font-weight-bold mb-0">{{ __('Additional Information') }}</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">{{ __('Received By') }}</label>
                        <p class="mb-0">{{ $payment->receivedBy->name ?? '-' }}</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="text-muted small">{{ __('Recorded At') }}</label>
                        <p class="mb-0">{{ $payment->created_at->format('F d, Y H:i') }}</p>
                    </div>
                    @if($payment->notes)
                    <div class="col-12 mb-3">
                        <label class="text-muted small">{{ __('Notes') }}</label>
                        <p class="mb-0">{{ $payment->notes }}</p>
                    </div>
                    @endif
                    @if($payment->receipt_path)
                    <div class="col-12 mb-3">
                        <label class="text-muted small">{{ __('Receipt') }}</label>
                        <p class="mb-0">
                            <a href="{{ asset('storage/' . $payment->receipt_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="las la-file-pdf"></i> {{ __('View Receipt') }}
                            </a>
                        </p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

