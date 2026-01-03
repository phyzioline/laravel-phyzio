@extends('web.layouts.dashboard_master')

@section('title', __('Edit Payment'))
@section('header_title', __('Edit Payment'))

@section('content')
@if(!$clinic)
<div class="alert alert-warning">
    <i class="las la-exclamation-triangle"></i> {{ __('Please set up your clinic first.') }}
</div>
@else
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                <h5 class="card-title font-weight-bold mb-0">{{ __('Edit Payment Entry') }}</h5>
                <span class="badge bg-light text-dark">{{ $payment->payment_number }}</span>
            </div>
            <div class="card-body px-4">
                <form action="{{ route('clinic.payments.update', $payment->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">{{ __('Patient') }} <span class="text-danger">*</span></label>
                        <select name="patient_id" id="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                            <option value="">{{ __('Select Patient') }}</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ old('patient_id', $payment->patient_id) == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->full_name }} - {{ $patient->phone }}
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">{{ __('Invoice') }} ({{ __('Optional') }})</label>
                        <select name="invoice_id" id="invoice_id" class="form-select @error('invoice_id') is-invalid @enderror">
                            <option value="">{{ __('Select Invoice') }}</option>
                            @foreach($invoices as $invoice)
                                <option value="{{ $invoice->id }}" data-balance="{{ $invoice->remaining_balance + ($payment->invoice_id == $invoice->id ? $payment->payment_amount : 0) }}" {{ old('invoice_id', $payment->invoice_id) == $invoice->id ? 'selected' : '' }}>
                                    {{ $invoice->invoice_number }} - {{ __('Balance') }}: {{ number_format($invoice->remaining_balance + ($payment->invoice_id == $invoice->id ? $payment->payment_amount : 0), 2) }} {{ __('EGP') }}
                                </option>
                            @endforeach
                        </select>
                        @error('invoice_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Payment Date') }} <span class="text-danger">*</span></label>
                            <input type="date" name="payment_date" class="form-control @error('payment_date') is-invalid @enderror" 
                                   value="{{ old('payment_date', $payment->payment_date->format('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                            @error('payment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Payment Amount') }} <span class="text-danger">*</span></label>
                            <input type="number" name="payment_amount" id="payment_amount" step="0.01" min="0.01" 
                                   class="form-control @error('payment_amount') is-invalid @enderror" 
                                   value="{{ old('payment_amount', $payment->payment_amount) }}" required>
                            <small id="balance_warning" class="text-danger d-none"></small>
                            @error('payment_amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">{{ __('Payment Method') }} <span class="text-danger">*</span></label>
                        <select name="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                            <option value="">{{ __('Select Method') }}</option>
                            @foreach(['cash', 'pos_card', 'vodafone_cash', 'instapay', 'bank_transfer', 'mobile_wallet'] as $method)
                                <option value="{{ $method }}" {{ old('payment_method', $payment->payment_method) == $method ? 'selected' : '' }}>
                                    {{ ucfirst(str_replace('_', ' ', $method)) }}
                                </option>
                            @endforeach
                        </select>
                        @error('payment_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">{{ __('Notes') }}</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $payment->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">{{ __('Receipt') }} ({{ __('Optional') }})</label>
                        @if($payment->receipt_path)
                            <div class="mb-2">
                                <a href="{{ asset('storage/' . $payment->receipt_path) }}" target="_blank" class="btn btn-sm btn-outline-info">
                                    <i class="las la-file"></i> {{ __('View Existing Receipt') }}
                                </a>
                            </div>
                        @endif
                        <input type="file" name="receipt_path" class="form-control @error('receipt_path') is-invalid @enderror" 
                               accept="image/*,.pdf">
                        <small class="text-muted">{{ __('Upload new receipt to replace the old one.') }}</small>
                        @error('receipt_path')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('clinic.payments.show', $payment->id) }}" class="btn btn-secondary">
                            <i class="las la-times"></i> {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary px-5">
                            <i class="las la-save"></i> {{ __('Update Payment') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
