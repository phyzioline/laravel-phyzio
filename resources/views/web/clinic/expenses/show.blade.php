@extends('web.layouts.dashboard_master')

@section('title', __('View Expense'))
@section('header_title', __('Expense Details'))

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
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title font-weight-bold mb-0">{{ __('Expense Details') }}</h5>
                    <div>
                        <a href="{{ route('clinic.expenses.edit', $expense) }}" class="btn btn-warning btn-sm">
                            <i class="las la-edit"></i> {{ __('Edit') }}
                        </a>
                        <a href="{{ route('clinic.expenses.index') }}" class="btn btn-secondary btn-sm">
                            <i class="las la-arrow-left"></i> {{ __('Back') }}
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body px-4">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>{{ __('Expense Number') }}:</strong>
                        <p>{{ $expense->expense_number }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>{{ __('Expense Date') }}:</strong>
                        <p>{{ $expense->expense_date->format('Y-m-d') }}</p>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>{{ __('Category') }}:</strong>
                        <p><span class="badge bg-info">{{ $expense->category_name }}</span></p>
                    </div>
                    <div class="col-md-6">
                        <strong>{{ __('Amount') }}:</strong>
                        <p class="text-danger"><strong>{{ number_format($expense->amount, 2) }} {{ __('EGP') }}</strong></p>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>{{ __('Payment Method') }}:</strong>
                        <p><span class="badge bg-secondary">{{ $expense->payment_method_name }}</span></p>
                    </div>
                    <div class="col-md-6">
                        <strong>{{ __('Vendor / Supplier') }}:</strong>
                        <p>{{ $expense->vendor_supplier ?? '-' }}</p>
                    </div>
                </div>
                
                <div class="mb-3">
                    <strong>{{ __('Description') }}:</strong>
                    <p>{{ $expense->description }}</p>
                </div>
                
                @if($expense->attachment)
                <div class="mb-3">
                    <strong>{{ __('Attachment') }}:</strong>
                    <p>
                        <a href="{{ Storage::url($expense->attachment) }}" target="_blank" class="btn btn-info btn-sm">
                            <i class="las la-file"></i> {{ __('View Attachment') }}
                        </a>
                    </p>
                </div>
                @endif
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>{{ __('Created By') }}:</strong>
                        <p>{{ $expense->creator->name ?? '-' }}</p>
                    </div>
                    <div class="col-md-6">
                        <strong>{{ __('Created At') }}:</strong>
                        <p>{{ $expense->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

