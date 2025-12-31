@extends('web.layouts.dashboard_master')

@section('title', __('Create Invoice'))
@section('header_title', __('Create Patient Invoice'))

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
                <h5 class="card-title font-weight-bold mb-0">{{ __('New Patient Invoice') }}</h5>
            </div>
            <div class="card-body px-4">
                <form action="{{ route('clinic.invoices.store') }}" method="POST">
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label">{{ __('Patient') }} <span class="text-danger">*</span></label>
                        <select name="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                            <option value="">{{ __('Select Patient') }}</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ old('patient_id', $selectedPatientId ?? null) == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->full_name }} - {{ $patient->phone }}
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">{{ __('Treatment Plan / Sessions Package') }}</label>
                        <input type="text" name="treatment_plan" class="form-control @error('treatment_plan') is-invalid @enderror" 
                               value="{{ old('treatment_plan') }}" placeholder="{{ __('e.g., 12 Sessions Physical Therapy') }}">
                        @error('treatment_plan')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Total Treatment Cost') }} <span class="text-danger">*</span></label>
                            <input type="number" name="total_cost" step="0.01" min="0" class="form-control @error('total_cost') is-invalid @enderror" 
                                   value="{{ old('total_cost') }}" required>
                            @error('total_cost')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Discount') }}</label>
                            <input type="number" name="discount" step="0.01" min="0" class="form-control @error('discount') is-invalid @enderror" 
                                   value="{{ old('discount', 0) }}">
                            @error('discount')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Invoice Date') }} <span class="text-danger">*</span></label>
                            <input type="date" name="invoice_date" class="form-control @error('invoice_date') is-invalid @enderror" 
                                   value="{{ old('invoice_date', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                            @error('invoice_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">{{ __('Due Date') }}</label>
                            <input type="date" name="due_date" class="form-control @error('due_date') is-invalid @enderror" 
                                   value="{{ old('due_date') }}">
                            @error('due_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">{{ __('Notes') }}</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('clinic.invoices.index') }}" class="btn btn-secondary">
                            <i class="las la-times"></i> {{ __('Cancel') }}
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="las la-save"></i> {{ __('Create Invoice') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

