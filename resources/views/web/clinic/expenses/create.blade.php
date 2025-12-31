@extends('web.layouts.dashboard_master')

@section('title', __('Add Daily Expense'))
@section('header_title', __('Add Daily Expense'))

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
                <h5 class="card-title font-weight-bold mb-0">{{ __('New Expense Entry') }}</h5>
            </div>
            <div class="card-body px-4">
                <form action="{{ route('clinic.expenses.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Expense Date') }} <span class="text-danger">*</span></label>
                            <input type="date" name="expense_date" class="form-control @error('expense_date') is-invalid @enderror" 
                                   value="{{ old('expense_date', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                            @error('expense_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Category') }} <span class="text-danger">*</span></label>
                            <select name="category" class="form-select @error('category') is-invalid @enderror" required>
                                <option value="">{{ __('Select Category') }}</option>
                                <option value="rent" {{ old('category') == 'rent' ? 'selected' : '' }}>{{ __('Rent') }}</option>
                                <option value="salaries" {{ old('category') == 'salaries' ? 'selected' : '' }}>{{ __('Salaries') }}</option>
                                <option value="utilities" {{ old('category') == 'utilities' ? 'selected' : '' }}>{{ __('Utilities') }}</option>
                                <option value="medical_supplies" {{ old('category') == 'medical_supplies' ? 'selected' : '' }}>{{ __('Medical Supplies') }}</option>
                                <option value="equipment_maintenance" {{ old('category') == 'equipment_maintenance' ? 'selected' : '' }}>{{ __('Equipment Maintenance') }}</option>
                                <option value="marketing" {{ old('category') == 'marketing' ? 'selected' : '' }}>{{ __('Marketing') }}</option>
                                <option value="transportation" {{ old('category') == 'transportation' ? 'selected' : '' }}>{{ __('Transportation') }}</option>
                                <option value="miscellaneous" {{ old('category') == 'miscellaneous' ? 'selected' : '' }}>{{ __('Miscellaneous') }}</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">{{ __('Description') }} <span class="text-danger">*</span></label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3" required>{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Amount') }} <span class="text-danger">*</span></label>
                            <input type="number" name="amount" step="0.01" min="0.01" class="form-control @error('amount') is-invalid @enderror" 
                                   value="{{ old('amount') }}" required>
                            @error('amount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
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
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">{{ __('Vendor / Supplier') }}</label>
                        <input type="text" name="vendor_supplier" class="form-control @error('vendor_supplier') is-invalid @enderror" 
                               value="{{ old('vendor_supplier') }}">
                        @error('vendor_supplier')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">{{ __('Attachment') }} ({{ __('Invoice/Receipt') }})</label>
                        <input type="file" name="attachment" class="form-control @error('attachment') is-invalid @enderror" 
                               accept="image/*,.pdf">
                        <small class="text-muted">{{ __('Max size: 10MB. Formats: JPG, PNG, PDF') }}</small>
                        @error('attachment')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('clinic.expenses.index') }}" class="btn btn-secondary">
                            <i class="las la-times"></i> {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="las la-save"></i> {{ __('Save Expense') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

