
@extends('dashboard.layouts.app')

@push('styles')
<style>
    /* Amazon-style Finance Dashboard Overrides */
    .finance-wrapper {
        font-family: "Amazon Ember", Arial, sans-serif;
        color: #0F1111;
        background-color: #ffffff; /* Main background white as requested for header area */
        min-height: 100vh;
    }
    
    .finance-body {
        background-color: #F2F4F8; /* Light gray for content area */
        min-height: calc(100vh - 120px);
        padding: 20px;
    }

    .finance-header {
        background-color: #fff;
        padding: 15px 20px;
        /* border-bottom: 1px solid #D5D9D9; removed to merge with tabs */
    }

    .finance-tabs {
        border-bottom: 1px solid #D5D9D9;
        background-color: #fff;
        padding: 0 20px;
    }

    .finance-tab-link {
        display: inline-block;
        padding: 12px 18px;
        color: #565959;
        font-weight: 500; /* Regular weight unselected */
        text-decoration: none;
        border-bottom: 3px solid transparent;
        font-size: 14px;
        margin-right: 5px;
    }

    .finance-tab-link:hover {
        color: #e77600; /* Amazon Orange hover */
        border-bottom-color: #e77600;
        background-color: #f6f6f6;
    }

    .finance-tab-link.active {
        color: #0F1111;
        font-weight: 700;
        border-bottom: 3px solid #e77600; /* Amazon Orange */
        background-color: transparent;
    }

    /* Filters Section */
    .finance-filters {
        background-color: #fff; 
        padding: 15px;
        border: 1px solid #D5D9D9; 
        border-radius: 4px; 
        margin-bottom: 15px;
    }
    
    .finance-form-label {
        font-size: 13px;
        font-weight: 700;
        color: #0F1111;
        margin-bottom: 4px;
        display: block;
    }

    .finance-input, .finance-select {
        border: 1px solid #888C8C;
        border-radius: 3px; 
        box-shadow: 0 1px 2px rgba(15,17,17,0.15) inset;
        padding: 4px 10px;
        font-size: 13px;
        height: 31px; 
        line-height: normal;
        background: #fff;
        color: #0F1111;
        width: 100%;
    }
    
    .finance-input:focus, .finance-select:focus {
        border-color: #e77600;
        box-shadow: 0 0 3px 2px rgba(228, 121, 17, 0.5);
        outline: none;
    }

    .btn-amazon-primary {
        background: #007185; /* Amazon Blue/Teal */
        border: 1px solid #007185;
        color: #fff;
        border-radius: 3px;
        padding: 0 15px;
        height: 31px;
        font-size: 13px;
        font-weight: 400;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
    }

    .btn-amazon-primary:hover {
        background: #005f70;
        border-color: #005f70;
        color: #fff;
    }
    
    .btn-amazon-secondary {
        background: #fff;
        border: 1px solid #D5D9D9;
        color: #0F1111;
        border-radius: 8px; /* Slightly more rounded like generic pills */
        box-shadow: 0 2px 5px rgba(213,217,217,0.5);
        padding: 0 15px;
        height: 29px;
        font-size: 13px;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    
    .btn-amazon-secondary:hover {
        background: #F7FAFA;
        border-color: #D5D9D9;
        color: #0F1111;
    }

    /* Alerts */
    .amazon-alert {
        border: 1px solid;
        border-radius: 4px;
        padding: 14px 18px;
        margin-bottom: 14px;
        font-size: 13px;
        display: flex;
        align-items: flex-start;
        position: relative;
    }

    .amazon-alert-info {
        background-color: #F0F6FC; /* Light Blue */
        border-color: #007185;
        color: #0F1111;
    }
    
    .amazon-alert-warning {
        background-color: #FFF4E5; /* Light Orange */
        border-color: #F08800;
        color: #0F1111;
    }

    .alert-icon {
        color: #007185;
        margin-right: 10px;
        font-size: 18px;
        margin-top: -2px;
    }
    
    .alert-close {
        position: absolute;
        top: 10px;
        right: 15px;
        cursor: pointer;
        font-size: 16px;
        color: #565959;
    }

    /* Table */
    .finance-table-container {
        border: 1px solid #D5D9D9;
        border-radius: 4px;
        overflow: hidden;
        background: #fff;
    }
    
    .finance-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        background: #fff;
    }

    .finance-table th {
        background-color: #F0F2F2; /* Light gray header */
        border-bottom: 1px solid #D5D9D9;
        text-align: left;
        padding: 10px 10px;
        font-weight: 700;
        color: #0F1111;
        white-space: nowrap;
        font-size: 12px;
        text-transform: uppercase;
    }

    .finance-table td {
        border-bottom: 1px solid #F0F2F2; /* Very light separator */
        padding: 12px 10px;
        color: #0F1111;
        vertical-align: top;
    }
    
    .finance-table tr:last-child td {
        border-bottom: none;
    }

    .finance-table tbody tr:hover {
        background-color: #F7FAFA;
    }
    
    .amount-positive { color: #007600; } /* Green */
    .amount-negative { color: #CC0C39; } /* Red */
    .amount-neutral { color: #565959; }

    /* Custom Date Range */
    .date-range-group {
        display: flex;
        gap: 10px;
        align-items: center;
    }
    
    .radio-group label {
        font-size: 13px;
        margin-left: 5px;
        color: #0F1111;
    }
    
    .radio-item {
        margin-bottom: 5px;
        display: flex;
        align-items: center;
    }
</style>
@endpush

@section('content')
<div class="finance-wrapper">
    {{-- Header --}}
    <div class="finance-header d-flex flex-column flex-md-row justify-content-between align-items-md-center">
        <div class="mb-3 mb-md-0">
             {{-- Logo logic if needed, or just Title --}}
            <h4 class="mb-0 fw-bold" style="color: #0F1111;">Payments &amp; Financials</h4>
        </div>
        
        {{-- Language / Tools --}}
        <div class="d-flex align-items-center flex-wrap">
           <a href="#" class="text-decoration-none text-dark me-3" style="font-size: 13px;">Learn More</a>
           <a href="#" class="text-decoration-none text-dark me-3" style="font-size: 13px;">Take Tour</a>
           <a href="#" class="text-decoration-none text-dark me-3" style="font-size: 13px;">Rate this page</a>
           <span class="text-muted d-none d-md-inline me-3">|</span>
           
           {{-- Arabic Translation Toggle --}}
           <a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale() == 'en' ? 'ar' : 'en') }}" class="btn btn-link text-dark text-decoration-none p-0 d-flex align-items-center">
                <i class="fa fa-globe me-1"></i> {{ LaravelLocalization::getCurrentLocale() == 'en' ? 'AR' : 'EN' }}
           </a>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="finance-tabs d-flex align-items-center overflow-auto">
        <a href="{{ route('dashboard.payments.index', ['view' => 'statement']) }}" 
           class="finance-tab-link {{ $currentView === 'statement' ? 'active' : '' }}">
           Statement View
        </a>
        <a href="{{ route('dashboard.payments.index', ['view' => 'transaction']) }}" 
           class="finance-tab-link {{ $currentView === 'transaction' ? 'active' : '' }}">
           Transaction View
        </a>
        <a href="#" class="finance-tab-link">All Statements</a>
        <a href="#" class="finance-tab-link">Disbursements</a>
        <a href="#" class="finance-tab-link">Advertising Invoice History</a>
        <a href="#" class="finance-tab-link">Reports Repository</a>
        <span class="badge bg-primary rounded-pill ms-1" style="font-size: 9px; vertical-align: super;">New</span>
    </div>

    <div class="finance-body">
        @yield('finance-content')
    </div>
</div>
@endsection
