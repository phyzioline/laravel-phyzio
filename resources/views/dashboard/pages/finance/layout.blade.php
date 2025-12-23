
@extends('dashboard.layouts.app')

@push('styles')
<style>
    /* CRITICAL FIX: Finance Dashboard Must Respect Header & Sidebar */
    
    /* Ensure page-wrapper container is positioned correctly */
    .page-wrapper {
        margin-left: 260px; /* Sidebar width */
        margin-top: 60px; /* Header height */
        transition: all 0.3s;
        width: calc(100% - 260px);
        box-sizing: border-box;
    }
    
    /* When sidebar is toggled (collapsed) */
    body.toggled .page-wrapper {
        margin-left: 0;
        width: 100%;
    }
    
    /* Mobile responsive */
    @media (max-width: 991px) {
        .page-wrapper {
            margin-left: 0 !important;
            width: 100% !important;
        }
    }
    
    /* Local Finance Page Adjustments - FIXED TO RESPECT HEADER & SIDEBAR */
    .finance-wrapper {
        background-color: #ffffff; 
        min-height: calc(100vh - 60px);
        width: 100%;
        max-width: 100%;
        margin: 0;
        padding: 0;
        overflow-x: hidden;
        box-sizing: border-box;
    }
    
    /* Finance header inside page-wrapper respects global layout */
    header.finance-top-bar {
        background: white;
        border-bottom: 1px solid #ddd;
        padding: 8px 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        box-sizing: border-box;
        position: relative;
    }
    
    .finance-body {
        background-color: #F2F4F8;
        min-height: calc(100vh - 160px);
        padding: 15px 20px;
        width: 100%;
        box-sizing: border-box;
    }

    .finance-header {
        background-color: #fff;
        padding: 10px 20px; /* Tighter */
    }

    .finance-tabs {
        border-bottom: 1px solid #D5D9D9;
        background-color: #fff;
        padding: 0 20px;
    }

    .finance-tab-link {
        display: inline-block;
        padding: 8px 14px; /* Smaller tabs */
        color: #565959;
        font-weight: 500; /* Regular weight unselected */
        text-decoration: none;
        border-bottom: 3px solid transparent;
        font-size: 13px; /* 13px Tabs */
        margin-right: 2px;
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
        padding: 12px; /* Tighter */
        border: 1px solid #D5D9D9; 
        border-radius: 4px; 
        margin-bottom: 15px;
    }
    
    .finance-form-label {
        font-size: 12px; /* Smaller label */
        font-weight: 700;
        color: #0F1111;
        margin-bottom: 4px;
        display: block;
    }

    .finance-input, .finance-select {
        border: 1px solid #888C8C;
        border-radius: 3px; 
        box-shadow: 0 1px 2px rgba(15,17,17,0.15) inset;
        padding: 3px 7px;
        font-size: 13px;
        height: 29px; /* Compact height */
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
        height: 29px;
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
        padding: 10px 14px; /* Compact */
        margin-bottom: 12px;
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
        top: 8px;
        right: 10px;
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
        padding: 6px 8px; /* Tighter padding */
        font-weight: 700;
        color: #0F1111;
        white-space: nowrap;
        font-size: 12px; /* 12px Header */
        text-transform: none;
    }

    .finance-table td {
        border-bottom: 1px solid #F0F2F2; /* Very light separator */
        padding: 6px 8px; /* Tighter padding */
        color: #0F1111;
        vertical-align: top;
        font-size: 12px; /* 12px Content */
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
<main class="page-wrapper">
<div class="finance-wrapper">
    {{-- Amazon Header Structure --}}
    <header class="finance-top-bar">
        <div>
            <h2 style="font-size: 18px; font-weight: 700; color: #000; margin: 0;">Payments Dashboard</h2>
            <div class="test-links mt-1">
                 <a href="#" style="font-size: 13px; color: #007185; text-decoration: none;">Learn More</a> 
                 <span class="mx-1 text-muted">|</span>
                 <a href="#" style="font-size: 13px; color: #007185; text-decoration: none;">Take Tour</a>
                 <span class="mx-1 text-muted">|</span>
                 <a href="#" style="font-size: 13px; color: #007185; text-decoration: none;">Rate this page</a>
            </div>
        </div>
        
        <div class="d-flex align-items-center gap-3">
             {{-- Language Toggle --}}
           <a href="{{ LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale() == 'en' ? 'ar' : 'en') }}" 
              class="d-flex align-items-center text-dark text-decoration-none border rounded px-3 py-1" 
              style="background: #f8f8f8;">
                 <i class="fa fa-globe me-2"></i> 
                 <span class="fw-bold">{{ LaravelLocalization::getCurrentLocale() == 'en' ? 'AR' : 'EN' }}</span>
           </a>
        </div>
    </header>

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
        <a href="{{ route('dashboard.payments.index', ['view' => 'all-statements']) }}" 
           class="finance-tab-link {{ $currentView === 'all-statements' ? 'active' : '' }}">
           All Statements
        </a>
        <a href="{{ route('dashboard.payments.index', ['view' => 'disbursements']) }}" 
           class="finance-tab-link {{ $currentView === 'disbursements' ? 'active' : '' }}">
           Disbursements
        </a>
        <a href="{{ route('dashboard.payments.index', ['view' => 'advertising']) }}" 
           class="finance-tab-link {{ $currentView === 'advertising' ? 'active' : '' }}">
           Advertising Invoice History
        </a>
        <a href="{{ route('dashboard.payments.index', ['view' => 'reports']) }}" 
           class="finance-tab-link {{ $currentView === 'reports' ? 'active' : '' }}">
           Reports Repository
        </a>
        <span class="badge bg-primary rounded-pill ms-1" style="font-size: 9px; vertical-align: super;">New</span>
    </div>

    <div class="finance-body">
        @yield('finance-content')
    </div>
</div>
</main>
@endsection
