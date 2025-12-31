@extends('web.layouts.dashboard_master')

@section('title', __('Record Payment'))
@section('header_title', __('Record Patient Payment'))

@section('content')
@if(!$clinic)
<div class="alert alert-warning">
    <i class="las la-exclamation-triangle"></i> {{ __('Please set up your clinic first.') }}
</div>
@else
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="card-title font-weight-bold mb-0">{{ __('New Payment Entry') }}</h5>
            </div>
            <div class="card-body px-4">
                <form action="{{ route('clinic.payments.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">{{ __('Patient') }} <span class="text-danger">*</span></label>
                        <select name="patient_id" id="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                            <option value="">{{ __('Select Patient') }}</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ old('patient_id', request('patient_id')) == $patient->id ? 'selected' : '' }}>
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
                                <option value="{{ $invoice->id }}" data-balance="{{ $invoice->remaining_balance }}" {{ old('invoice_id') == $invoice->id ? 'selected' : '' }}>
                                    {{ $invoice->invoice_number }} - {{ __('Balance') }}: {{ number_format($invoice->remaining_balance, 2) }} {{ __('EGP') }}
                                </option>
                            @endforeach
                        </select>
                        <small class="text-muted">{{ __('Select an invoice to link this payment') }}</small>
                        @error('invoice_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Payment Date') }} <span class="text-danger">*</span></label>
                            <input type="date" name="payment_date" class="form-control @error('payment_date') is-invalid @enderror" 
                                   value="{{ old('payment_date', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                            @error('payment_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Payment Amount') }} <span class="text-danger">*</span></label>
                            <input type="number" name="payment_amount" id="payment_amount" step="0.01" min="0.01" 
                                   class="form-control @error('payment_amount') is-invalid @enderror" 
                                   value="{{ old('payment_amount') }}" required>
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
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>{{ __('Cash') }}</option>
                            <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>{{ __('Bank Transfer') }}</option>
                            <option value="pos_card" {{ old('payment_method') == 'pos_card' ? 'selected' : '' }}>{{ __('POS / Card') }}</option>
                            <option value="mobile_wallet" {{ old('payment_method') == 'mobile_wallet' ? 'selected' : '' }}>{{ __('Mobile Wallet') }}</option>
                        </select>
                        @error('payment_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">{{ __('Notes') }}</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" 
                                  placeholder="{{ __('e.g., Patient paid part of the amount today') }}">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">{{ __('Receipt') }} ({{ __('Optional') }})</label>
                        <input type="file" name="receipt_path" class="form-control @error('receipt_path') is-invalid @enderror" 
                               accept="image/*,.pdf">
                        <small class="text-muted">{{ __('Max size: 10MB. Formats: JPG, PNG, PDF') }}</small>
                        @error('receipt_path')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('clinic.payments.index') }}" class="btn btn-secondary">
                            <i class="las la-times"></i> {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="las la-save"></i> {{ __('Record Payment') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const invoiceSelect = document.getElementById('invoice_id');
    const paymentAmount = document.getElementById('payment_amount');
    const balanceWarning = document.getElementById('balance_warning');
    
    invoiceSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value && selectedOption.dataset.balance) {
            const balance = parseFloat(selectedOption.dataset.balance);
            balanceWarning.textContent = '{{ __("Remaining balance") }}: ' + balance.toFixed(2) + ' {{ __("EGP") }}';
            balanceWarning.classList.remove('d-none');
        } else {
            balanceWarning.classList.add('d-none');
        }
    });
    
    paymentAmount.addEventListener('input', function() {
        const selectedOption = invoiceSelect.options[invoiceSelect.selectedIndex];
        if (selectedOption.value && selectedOption.dataset.balance) {
            const balance = parseFloat(selectedOption.dataset.balance);
            const amount = parseFloat(this.value) || 0;
            if (amount > balance) {
                balanceWarning.textContent = '{{ __("Warning: Payment amount exceeds remaining balance!") }}';
                balanceWarning.classList.remove('d-none');
            } else {
                balanceWarning.textContent = '{{ __("Remaining balance") }}: ' + balance.toFixed(2) + ' {{ __("EGP") }}';
            }
        }
    });
    
    // Load invoices when patient changes
    document.getElementById('patient_id').addEventListener('change', function() {
        const patientId = this.value;
        if (patientId) {
            window.location.href = '{{ route("clinic.payments.create") }}?patient_id=' + patientId;
        }
    });
});
</script>
@endpush
@endif
@endsection

