@extends('dashboard.layouts.app')

@push('styles')
<style>
    /* Enhanced Dashboard Styles */
    .dashboard-welcome {
        background: linear-gradient(135deg, #02767F 0%, #04b8c4 100%);
        border-radius: 15px;
        padding: 2rem;
        color: white;
        margin-bottom: 2rem;
        box-shadow: 0 10px 30px rgba(2, 118, 127, 0.2);
    }
    
    .dashboard-welcome h2 {
        font-weight: 700;
        margin-bottom: 0.5rem;
    }
    
    .dashboard-welcome p {
        opacity: 0.9;
        margin-bottom: 0;
    }
    
    /* Enhanced Stat Cards */
    .stat-card {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
        position: relative;
        overflow: hidden;
    }
    
    .stat-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: var(--card-color);
        transition: width 0.3s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15) !important;
    }
    
    .stat-card:hover::before {
        width: 100%;
        opacity: 0.1;
    }
    
    .stat-card .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        margin-bottom: 1rem;
        background: var(--icon-bg);
        color: var(--icon-color);
    }
    
    .stat-card .stat-value {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
        color: var(--card-color);
    }
    
    .stat-card .stat-label {
        font-size: 0.875rem;
        color: #6c757d;
        margin-bottom: 0;
    }
    
    /* Chart Container Constraints */
    .chart-container {
        position: relative;
        width: 100%;
        max-width: 100%;
        overflow: hidden;
    }
    
    .chart-container canvas {
        max-width: 100% !important;
        max-height: 100% !important;
    }
    
    /* Enhanced Card Styles */
    .card {
        border: none;
        transition: all 0.3s ease;
    }
    
    .card:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1) !important;
    }
    
    .hover-lift {
        transition: all 0.3s ease;
    }
    
    .hover-lift:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15) !important;
    }
    
    /* Activity Feed */
    .activity-item {
        transition: all 0.2s ease;
        padding: 0.75rem;
        border-radius: 8px;
    }
    
    .activity-item:hover {
        background: #f8f9fa;
        transform: translateX(5px);
    }
    
    /* Quick Access Cards */
    .quick-access-card {
        transition: all 0.3s ease;
        text-align: center;
        padding: 1.5rem;
        border-radius: 12px;
    }
    
    .quick-access-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 12px 35px rgba(0, 0, 0, 0.15) !important;
    }
    
    .quick-access-card i {
        transition: all 0.3s ease;
    }
    
    .quick-access-card:hover i {
        transform: scale(1.2);
    }
    
    /* Pending Approvals Badge */
    .pending-badge {
        animation: pulse 2s infinite;
    }
    
    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.7;
        }
    }
    
    /* Performance Metrics */
    .metric-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
        border-left: 4px solid;
        transition: all 0.3s ease;
    }
    
    .metric-card:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }
    
    /* Responsive Design */
    @media (max-width: 768px) {
        .chart-container {
            height: 250px !important;
        }
        
        .dashboard-welcome {
            padding: 1.5rem;
        }
        
        .stat-card .stat-value {
            font-size: 1.5rem;
        }
    }
    
    /* Loading Animation */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .fade-in {
        animation: fadeIn 0.5s ease-out;
    }
    
    /* Custom Scrollbar */
    .activity-feed {
        max-height: 500px;
        overflow-y: auto;
    }
    
    .activity-feed::-webkit-scrollbar {
        width: 6px;
    }
    
    .activity-feed::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .activity-feed::-webkit-scrollbar-thumb {
        background: #02767F;
        border-radius: 10px;
    }
    
    .activity-feed::-webkit-scrollbar-thumb:hover {
        background: #04b8c4;
    }
</style>
@endpush

@section('content')
    <main class="page-wrapper">
        <div class="main-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">PHYZIOLINE | Dashboard</div>
            </div>

            @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('super-admin'))
                {{-- Welcome Section --}}
                <div class="dashboard-welcome fade-in">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2">
                                <i class="fa fa-hand-sparkles me-2"></i>
                                Welcome back, {{ auth()->user()->name }}!
                            </h2>
                            <p class="mb-0">
                                <i class="fa fa-calendar me-2"></i>
                                {{ now()->format('l, F j, Y') }} • 
                                <i class="fa fa-clock me-2"></i>
                                {{ now()->format('g:i A') }}
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="d-flex flex-column align-items-end">
                                <div class="mb-2">
                                    <span class="badge bg-light text-dark px-3 py-2">
                                        <i class="fa fa-shield-check me-1"></i>
                                        {{ auth()->user()->hasRole('super-admin') ? 'Super Admin' : 'Administrator' }}
                                    </span>
                                </div>
                                <small class="opacity-75">
                                    <i class="fa fa-chart-line me-1"></i>
                                    Platform Overview
                                </small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Key Statistics Cards --}}
                <div class="row g-3 mb-4">
                    <div class="col-12">
                        <h5 class="mb-3">
                            <i class="fa fa-chart-bar text-primary me-2"></i>
                            Platform Statistics
                        </h5>
                    </div>
                    
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="card rounded-3 border-0 shadow-sm stat-card" style="--card-color: #02767F; --icon-bg: rgba(2, 118, 127, 0.1); --icon-color: #02767F;">
                            <div class="card-body p-4">
                                <div class="stat-icon">
                                    <i class="fa fa-users"></i>
                                </div>
                                <div class="stat-value">{{ number_format($user ?? 0) }}</div>
                                <div class="stat-label">{{ __('Total Users') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="card rounded-3 border-0 shadow-sm stat-card" style="--card-color: #28a745; --icon-bg: rgba(40, 167, 69, 0.1); --icon-color: #28a745;">
                            <div class="card-body p-4">
                                <div class="stat-icon">
                                    <i class="fa fa-store"></i>
                                </div>
                                <div class="stat-value">{{ number_format($vendor ?? 0) }}</div>
                                <div class="stat-label">{{ __('Vendors') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="card rounded-3 border-0 shadow-sm stat-card" style="--card-color: #17a2b8; --icon-bg: rgba(23, 162, 184, 0.1); --icon-color: #17a2b8;">
                            <div class="card-body p-4">
                                <div class="stat-icon">
                                    <i class="fa fa-cart-shopping"></i>
                                </div>
                                <div class="stat-value">{{ number_format($buyer ?? 0) }}</div>
                                <div class="stat-label">{{ __('Buyers') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="card rounded-3 border-0 shadow-sm stat-card" style="--card-color: #ffc107; --icon-bg: rgba(255, 193, 7, 0.1); --icon-color: #ffc107;">
                            <div class="card-body p-4">
                                <div class="stat-icon">
                                    <i class="fa fa-box"></i>
                                </div>
                                <div class="stat-value">{{ number_format($product ?? 0) }}</div>
                                <div class="stat-label">{{ __('Products') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="card rounded-3 border-0 shadow-sm">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0 text-muted small">
                                        <i class="fa fa-store text-primary"></i> {{ __('Vendors') }}
                                    </h6>
                                </div>
                                <h3 class="mb-0 fw-bold" style="color: #02767F;">{{ $vendor ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="card rounded-3 border-0 shadow-sm">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0 text-muted small">
                                        <i class="fa fa-cart-shopping text-primary"></i> {{ __('Buyers') }}
                                    </h6>
                                </div>
                                <h3 class="mb-0 fw-bold" style="color: #02767F;">{{ $buyer ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="card rounded-3 border-0 shadow-sm">
                            <div class="card-body p-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="mb-0 text-muted small">
                                        <i class="fa fa-box text-primary"></i> {{ __('Products') }}
                                    </h6>
                                </div>
                                <h3 class="mb-0 fw-bold" style="color: #02767F;">{{ $product ?? 0 }}</h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="card rounded-3 border-0 shadow-sm stat-card" style="--card-color: #dc3545; --icon-bg: rgba(220, 53, 69, 0.1); --icon-color: #dc3545;">
                            <div class="card-body p-4">
                                <div class="stat-icon">
                                    <i class="fa fa-receipt"></i>
                                </div>
                                <div class="stat-value">{{ number_format($order ?? 0) }}</div>
                                <div class="stat-label">{{ __('Total Orders') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="card rounded-3 border-0 shadow-sm stat-card" style="--card-color: #6f42c1; --icon-bg: rgba(111, 66, 193, 0.1); --icon-color: #6f42c1;">
                            <div class="card-body p-4">
                                <div class="stat-icon">
                                    <i class="fa fa-credit-card"></i>
                                </div>
                                <div class="stat-value">{{ number_format($order_card ?? 0) }}</div>
                                <div class="stat-label">{{ __('Card Orders') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="card rounded-3 border-0 shadow-sm stat-card" style="--card-color: #fd7e14; --icon-bg: rgba(253, 126, 20, 0.1); --icon-color: #fd7e14;">
                            <div class="card-body p-4">
                                <div class="stat-icon">
                                    <i class="fa fa-money-bill"></i>
                                </div>
                                <div class="stat-value">{{ number_format($order_cash ?? 0) }}</div>
                                <div class="stat-label">{{ __('Cash Orders') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="card rounded-3 border-0 shadow-sm stat-card" style="--card-color: #20c997; --icon-bg: rgba(32, 201, 151, 0.1); --icon-color: #20c997;">
                            <div class="card-body p-4">
                                <div class="stat-icon">
                                    <i class="fa fa-tags"></i>
                                </div>
                                <div class="stat-value">{{ number_format($tag ?? 0) }}</div>
                                <div class="stat-label">{{ __('Tags') }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Ecosystem Stats --}}
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="card rounded-3 border-0 shadow-sm stat-card" style="--card-color: #28a745; --icon-bg: rgba(40, 167, 69, 0.1); --icon-color: #28a745;">
                            <div class="card-body p-4">
                                <div class="stat-icon">
                                    <i class="fa fa-user-md"></i>
                                </div>
                                <div class="stat-value">{{ number_format($therapist_count ?? 0) }}</div>
                                <div class="stat-label">{{ __('Therapists') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="card rounded-3 border-0 shadow-sm stat-card" style="--card-color: #17a2b8; --icon-bg: rgba(23, 162, 184, 0.1); --icon-color: #17a2b8;">
                            <div class="card-body p-4">
                                <div class="stat-icon">
                                    <i class="fa fa-clinic-medical"></i>
                                </div>
                                <div class="stat-value">{{ number_format($clinic_count ?? 0) }}</div>
                                <div class="stat-label">{{ __('Clinics') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="card rounded-3 border-0 shadow-sm stat-card" style="--card-color: #ffc107; --icon-bg: rgba(255, 193, 7, 0.1); --icon-color: #ffc107;">
                            <div class="card-body p-4">
                                <div class="stat-icon">
                                    <i class="fa fa-calendar-check"></i>
                                </div>
                                <div class="stat-value">{{ number_format($appointment_count ?? 0) }}</div>
                                <div class="stat-label">{{ __('Home Visits') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="card rounded-3 border-0 shadow-sm stat-card" style="--card-color: #dc3545; --icon-bg: rgba(220, 53, 69, 0.1); --icon-color: #dc3545;">
                            <div class="card-body p-4">
                                <div class="stat-icon">
                                    <i class="fa fa-graduation-cap"></i>
                                </div>
                                <div class="stat-value">{{ number_format($course_count ?? 0) }}</div>
                                <div class="stat-label">{{ __('Courses') }}</div>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Charts Section --}}
                <div class="row g-3 mt-4">
                    <div class="col-12">
                        <h5 class="mb-3">
                            <i class="fa fa-chart-pie text-primary me-2"></i>
                            Analytics & Insights
                        </h5>
                    </div>
                    
                    {{-- Payment Methods Chart --}}
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card rounded-3 border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="card-title mb-0 fw-bold">
                                        <i class="fa fa-credit-card text-primary me-2"></i>
                                        Payment Methods
                                    </h6>
                                </div>
                                <div class="chart-container" style="height: 250px;">
                                    <canvas id="paymentMethodsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- User Types Chart --}}
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card rounded-3 border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="card-title mb-0 fw-bold">
                                        <i class="fa fa-users text-success me-2"></i>
                                        User Distribution
                                    </h6>
                                </div>
                                <div class="chart-container" style="height: 250px;">
                                    <canvas id="userTypesChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Order Status Chart --}}
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card rounded-3 border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="card-title mb-0 fw-bold">
                                        <i class="fa fa-tasks text-warning me-2"></i>
                                        Order Status
                                    </h6>
                                </div>
                                <div class="chart-container" style="height: 250px;">
                                    <canvas id="orderStatusChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Sales Trend Chart --}}
                    <div class="col-lg-8 col-md-12 col-sm-12">
                        <div class="card rounded-3 border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="card-title mb-0 fw-bold">
                                        <i class="fa fa-chart-line text-info me-2"></i>
                                        Sales Trend (Last 30 Days)
                                    </h6>
                                    <span class="badge bg-primary">
                                        <i class="fa fa-dollar-sign me-1"></i>
                                        ${{ number_format($totalRevenue ?? 0, 2) }}
                                    </span>
                                </div>
                                <div class="chart-container" style="height: 280px;">
                                    <canvas id="salesTrendChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Ecosystem Overview Chart --}}
                    <div class="col-lg-4 col-md-12 col-sm-12">
                        <div class="card rounded-3 border-0 shadow-sm">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="card-title mb-0 fw-bold">
                                        <i class="fa fa-network-wired text-danger me-2"></i>
                                        Ecosystem Overview
                                    </h6>
                                </div>
                                <div class="chart-container" style="height: 280px;">
                                    <canvas id="ecosystemChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- System Health Indicators --}}
                <div class="row g-3 mt-4">
                    <div class="col-12">
                        <h5 class="mb-3">
                            <i class="fa fa-heartbeat text-danger me-2"></i>
                            System Health & Performance
                        </h5>
                    </div>
                    
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="card rounded-3 border-0 shadow-sm metric-card" style="border-left-color: #28a745 !important;">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="stat-icon" style="--icon-bg: rgba(40, 167, 69, 0.1); --icon-color: #28a745;">
                                        <i class="fa fa-dollar-sign"></i>
                                    </div>
                                    <span class="badge bg-success">Active</span>
                                </div>
                                <p class="text-muted mb-1 small">Total Revenue</p>
                                <h4 class="mb-0 fw-bold text-success">${{ number_format($totalRevenue ?? 0, 2) }}</h4>
                                <small class="text-muted">All time</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="card rounded-3 border-0 shadow-sm metric-card" style="border-left-color: #ffc107 !important;">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="stat-icon" style="--icon-bg: rgba(255, 193, 7, 0.1); --icon-color: #ffc107;">
                                        <i class="fa fa-calendar-day"></i>
                                    </div>
                                    <span class="badge bg-warning">Today</span>
                                </div>
                                <p class="text-muted mb-1 small">Today's Visits</p>
                                <h4 class="mb-0 fw-bold text-warning">{{ $todayAppointments ?? 0 }}</h4>
                                <small class="text-muted">Appointments scheduled</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="card rounded-3 border-0 shadow-sm metric-card" style="border-left-color: #17a2b8 !important;">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="stat-icon" style="--icon-bg: rgba(23, 162, 184, 0.1); --icon-color: #17a2b8;">
                                        <i class="fa fa-check-circle"></i>
                                    </div>
                                    <span class="badge bg-info">Online</span>
                                </div>
                                <p class="text-muted mb-1 small">Platform Status</p>
                                <h4 class="mb-0 fw-bold text-info">Operational</h4>
                                <small class="text-muted">All systems running</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="card rounded-3 border-0 shadow-sm metric-card" style="border-left-color: #6c757d !important;">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div class="stat-icon" style="--icon-bg: rgba(108, 117, 125, 0.1); --icon-color: #6c757d;">
                                        <i class="fa fa-server"></i>
                                    </div>
                                    <span class="badge bg-secondary">Stable</span>
                                </div>
                                <p class="text-muted mb-1 small">System Status</p>
                                <h4 class="mb-0 fw-bold text-secondary">Healthy</h4>
                                <small class="text-muted">No issues detected</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick Access Cards --}}
                <div class="row g-3 mt-4">
                    <div class="col-12">
                        <h5 class="mb-3">
                            <i class="fa fa-bolt text-warning me-2"></i>
                            Quick Access
                        </h5>
                    </div>

                    <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                        <a href="{{ route('dashboard.verifications.index') }}" class="text-decoration-none">
                            <div class="card rounded-3 border-0 shadow-sm quick-access-card h-100">
                                <div class="card-body text-center p-4">
                                    <i class="fa fa-shield-check fa-2x mb-3" style="color: #02767F;"></i>
                                    <h6 class="fw-bold mb-0">Verifications</h6>
                                    @if(collect($pendingApprovals)->sum('count') > 0)
                                        <span class="badge bg-danger pending-badge mt-2">
                                            {{ collect($pendingApprovals)->sum('count') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                        <a href="{{ route('dashboard.users.index') }}" class="text-decoration-none">
                            <div class="card rounded-3 border-0 shadow-sm quick-access-card h-100">
                                <div class="card-body text-center p-4">
                                    <i class="fa fa-users fa-2x mb-3" style="color: #02767F;"></i>
                                    <h6 class="fw-bold mb-0">Users</h6>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                        <a href="{{ route('dashboard.therapist_profiles.index') }}" class="text-decoration-none">
                            <div class="card rounded-3 border-0 shadow-sm quick-access-card h-100">
                                <div class="card-body text-center p-4">
                                    <i class="fa fa-user-md fa-2x mb-3 text-success"></i>
                                    <h6 class="fw-bold mb-0">Therapists</h6>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                        <a href="{{ route('dashboard.home_visits.index') }}" class="text-decoration-none">
                            <div class="card rounded-3 border-0 shadow-sm quick-access-card h-100">
                                <div class="card-body text-center p-4">
                                    <i class="fa fa-calendar-check fa-2x mb-3 text-warning"></i>
                                    <h6 class="fw-bold mb-0">Home Visits</h6>
                                    @if(($todayAppointments ?? 0) > 0)
                                        <span class="badge bg-warning mt-2">{{ $todayAppointments }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                        <a href="{{ route('dashboard.clinic_profiles.index') }}" class="text-decoration-none">
                            <div class="card rounded-3 border-0 shadow-sm quick-access-card h-100">
                                <div class="card-body text-center p-4">
                                    <i class="fa fa-clinic-medical fa-2x mb-3 text-info"></i>
                                    <h6 class="fw-bold mb-0">Clinics</h6>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                        <a href="{{ route('dashboard.courses.index') }}" class="text-decoration-none">
                            <div class="card rounded-3 border-0 shadow-sm quick-access-card h-100">
                                <div class="card-body text-center p-4">
                                    <i class="fa fa-graduation-cap fa-2x mb-3 text-danger"></i>
                                    <h6 class="fw-bold mb-0">Courses</h6>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                        <a href="{{ route('dashboard.products.index') }}" class="text-decoration-none">
                            <div class="card rounded-3 border-0 shadow-sm quick-access-card h-100">
                                <div class="card-body text-center p-4">
                                    <i class="fa fa-box fa-2x mb-3" style="color: #02767F;"></i>
                                    <h6 class="fw-bold mb-0">Products</h6>
                                    @if(($lowStockProducts ?? 0) > 0)
                                        <span class="badge bg-danger mt-2">{{ $lowStockProducts }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                        <a href="{{ route('dashboard.orders.index') }}" class="text-decoration-none">
                            <div class="card rounded-3 border-0 shadow-sm quick-access-card h-100">
                                <div class="card-body text-center p-4">
                                    <i class="fa fa-receipt fa-2x mb-3 text-success"></i>
                                    <h6 class="fw-bold mb-0">Orders</h6>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-2 col-md-3 col-sm-4 col-6">
                        <a href="{{ route('dashboard.settings.show') }}" class="text-decoration-none">
                            <div class="card rounded-3 border-0 shadow-sm quick-access-card h-100">
                                <div class="card-body text-center p-4">
                                    <i class="fa fa-cog fa-2x mb-3 text-secondary"></i>
                                    <h6 class="fw-bold mb-0">Settings</h6>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                {{-- Pending Approvals --}}
                @if(collect($pendingApprovals)->sum('count') > 0)
                <div class="row g-3 mt-3">
                    <div class="col-12">
                        <h6 class="mb-2"><i class="fa fa-clock text-warning"></i> Pending Approvals</h6>
                    </div>

                    @foreach($pendingApprovals as $pending)
                        @if($pending['count'] > 0)
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <div class="card rounded-3 border-0 shadow-sm">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <div>
                                            <h6 class="mb-0 small fw-bold">{{ $pending['title'] }}</h6>
                                            <p class="text-muted mb-0" style="font-size: 0.75rem;">Requires attention</p>
                                        </div>
                                        <div class="text-center">
                                            <span class="badge bg-{{ $pending['color'] }} rounded-circle p-2" style="font-size: 1rem; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;">
                                                {{ $pending['count'] }}
                                            </span>
                                        </div>
                                    </div>
                                    <a href="{{ $pending['link'] }}" class="btn btn-sm btn-outline-{{ $pending['color'] }} w-100 mt-2">
                                        <i class="fa {{ $pending['icon'] }}"></i> Review
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endif
                    @endforeach
                </div>
                @endif

                {{-- Recent Activity Feed --}}
                <div class="row g-4 mt-4">
                    <div class="col-12">
                        <div class="card rounded-4 border-0 shadow-sm">
                            <div class="card-header bg-white border-0 pt-4 pb-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h5 class="mb-1">
                                            <i class="fa fa-stream text-primary me-2"></i>
                                            Recent Activity
                                        </h5>
                                        <small class="text-muted">Latest platform actions and updates</small>
                                    </div>
                                    <span class="badge bg-primary">
                                        {{ $recentActivity && count($recentActivity) > 0 ? count($recentActivity) : 0 }} items
                                    </span>
                                </div>
                            </div>
                            <div class="card-body">
                                @if($recentActivity && count($recentActivity) > 0)
                                    <div class="activity-feed">
                                        @foreach($recentActivity as $activity)
                                        <div class="activity-item d-flex align-items-start mb-3 pb-3 border-bottom">
                                            <div class="activity-icon me-3">
                                                <span class="badge bg-{{ $activity['color'] }} rounded-circle p-3" style="width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;">
                                                    <i class="fa {{ $activity['icon'] }}"></i>
                                                </span>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-bold">{{ $activity['title'] }}</h6>
                                                <p class="mb-1 text-muted">{{ $activity['description'] }}</p>
                                                <small class="text-muted">
                                                    <i class="fa fa-clock me-1"></i>
                                                    {{ $activity['time']->diffForHumans() }}
                                                </small>
                                            </div>
                                            <a href="{{ $activity['link'] }}" class="btn btn-sm btn-outline-{{ $activity['color'] }} ms-2" title="View Details">
                                                <i class="fa fa-arrow-right"></i>
                                            </a>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="fa fa-inbox fa-3x text-muted mb-3 opacity-50"></i>
                                        <p class="text-muted mb-0">No recent activity</p>
                                        <small class="text-muted">Activity will appear here as users interact with the platform</small>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                @push('scripts')
                <script>
                    // Payment Methods Chart
                    const paymentCtx = document.getElementById('paymentMethodsChart').getContext('2d');
                    new Chart(paymentCtx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Cash Orders', 'Card Orders'],
                            datasets: [{
                                data: [{{ $order_cash ?? 0 }}, {{ $order_card ?? 0 }}],
                                backgroundColor: ['#36a2eb', '#4bc0c0'],
                                borderWidth: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                }
                            }
                        }
                    });

                    // User Types Chart
                    const userCtx = document.getElementById('userTypesChart').getContext('2d');
                    new Chart(userCtx, {
                        type: 'pie',
                        data: {
                            labels: ['Vendors', 'Buyers', 'Others'],
                            datasets: [{
                                data: [{{ $vendor ?? 0 }}, {{ $buyer ?? 0 }}, {{ ($user ?? 0) - ($vendor ?? 0) - ($buyer ?? 0) }}],
                                backgroundColor: ['#ff6384', '#ffcd56', '#9966ff'],
                                borderWidth: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                }
                            }
                        }
                    });

                    // Order Status Chart
                    const orderStatusCtx = document.getElementById('orderStatusChart');
                    if (orderStatusCtx) {
                        new Chart(orderStatusCtx.getContext('2d'), {
                            type: 'doughnut',
                            data: {
                                labels: ['Pending', 'Processing', 'Completed', 'Cancelled'],
                                datasets: [{
                                    data: [
                                        {{ \App\Models\Order::where('status', 'pending')->count() }},
                                        {{ \App\Models\Order::where('status', 'processing')->count() }},
                                        {{ \App\Models\Order::where('status', 'completed')->count() }},
                                        {{ \App\Models\Order::where('status', 'cancelled')->count() }}
                                    ],
                                    backgroundColor: ['#ffc107', '#17a2b8', '#28a745', '#dc3545'],
                                    borderWidth: 0
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom',
                                    }
                                }
                            }
                        });
                    }

                    // Sales Trend Chart (Last 30 Days)
                    const salesTrendCtx = document.getElementById('salesTrendChart');
                    if (salesTrendCtx) {
                        // Get sales data from server (processed in controller)
                        const salesDataRaw = @json($salesTrendData ?? []);
                        
                        // Generate all 30 days labels
                        const labels = [];
                        for (let i = 29; i >= 0; i--) {
                            const date = new Date();
                            date.setDate(date.getDate() - i);
                            labels.push(date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' }));
                        }
                        
                        // Map sales data to labels (fill missing days with 0)
                        const data = labels.map(label => salesDataRaw[label] || 0);
                        
                        new Chart(salesTrendCtx.getContext('2d'), {
                            type: 'line',
                            data: {
                                labels: labels,
                                datasets: [{
                                    label: 'Sales ($)',
                                    data: data,
                                    borderColor: '#02767F',
                                    backgroundColor: 'rgba(2, 118, 127, 0.1)',
                                    tension: 0.4,
                                    fill: true,
                                    pointRadius: 3,
                                    pointHoverRadius: 5
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        ticks: {
                                            callback: function(value) {
                                                return '$' + value.toFixed(0);
                                            }
                                        }
                                    }
                                },
                                plugins: {
                                    legend: {
                                        display: false
                                    },
                                    tooltip: {
                                        callbacks: {
                                            label: function(context) {
                                                return 'Sales: $' + context.parsed.y.toFixed(2);
                                            }
                                        }
                                    }
                                }
                            }
                        });
                    }

                    // Ecosystem Overview Chart
                    const ecosystemCtx = document.getElementById('ecosystemChart');
                    if (ecosystemCtx) {
                        new Chart(ecosystemCtx.getContext('2d'), {
                            type: 'bar',
                            data: {
                                labels: ['Products', 'Orders', 'Therapists', 'Clinics', 'Appointments', 'Courses'],
                                datasets: [{
                                    label: 'Count',
                                    data: [
                                        {{ $product ?? 0 }}, 
                                        {{ $order ?? 0 }}, 
                                        {{ $therapist_count ?? 0 }}, 
                                        {{ $clinic_count ?? 0 }}, 
                                        {{ $appointment_count ?? 0 }}, 
                                        {{ $course_count ?? 0 }}
                                    ],
                                    backgroundColor: [
                                        '#02767F',
                                        '#04b8c4',
                                        '#28a745',
                                        '#17a2b8',
                                        '#ffc107',
                                        '#dc3545'
                                    ],
                                    borderWidth: 0
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                },
                                plugins: {
                                    legend: {
                                        display: false
                                    }
                                }
                            }
                        });
                    }
                </script>
                @endpush
            @elseif (auth()->user()->hasRole('vendor'))
                {{-- Hero Section with Proper Spacing --}}
                <div class="vendor-dashboard-hero" style="margin-top: 2cm; margin-bottom: 2rem;">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <h2 class="mb-2">Welcome back, {{ auth()->user()->name }}!</h2>
                            <p class="text-muted">Here's what's happening with your store today.</p>
                        </div>
                        <div class="col-md-4 text-end">
                            <a href="{{ route('dashboard.products.create') }}" class="btn btn-primary">
                                <i class="fa fa-plus"></i> Add New Product
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Statistics Cards --}}
                <div class="row g-4">
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="card rounded-4 border-0 shadow-sm" style="border-left: 4px solid #02767F !important;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                    <h6 class="mb-0 text-muted">
                                        <i class="fa fa-box text-primary"></i> Total Products
                                    </h6>
                                </div>
                                <h2 class="mt-3 fw-bold" style="color: #02767F;">{{ $product_only ?? 0 }}</h2>
                                <small class="text-muted">Active listings</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="card rounded-4 border-0 shadow-sm" style="border-left: 4px solid #28a745 !important;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                    <h6 class="mb-0 text-muted">
                                        <i class="fa fa-receipt text-success"></i> Total Orders
                                    </h6>
                                </div>
                                <h2 class="mt-3 fw-bold text-success">{{ $order_only ?? 0 }}</h2>
                                <small class="text-muted">{{ $completed_orders ?? 0 }} completed</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="card rounded-4 border-0 shadow-sm" style="border-left: 4px solid #ffc107 !important;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                    <h6 class="mb-0 text-muted">
                                        <i class="fa fa-dollar-sign text-warning"></i> Revenue Earned
                                    </h6>
                                </div>
                                <h2 class="mt-3 fw-bold text-warning">${{ number_format($revenue_only ?? 0, 2) }}</h2>
                                <small class="text-muted">Paid out</small>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="card rounded-4 border-0 shadow-sm" style="border-left: 4px solid #17a2b8 !important;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                    <h6 class="mb-0 text-muted">
                                        <i class="fa fa-clock text-info"></i> Pending Payments
                                    </h6>
                                </div>
                                <h2 class="mt-3 fw-bold text-info">${{ number_format($pending_payments ?? 0, 2) }}</h2>
                                <small class="text-muted">Awaiting payout</small>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Charts Section --}}
                <div class="row g-4 mt-3">
                    {{-- Monthly Sales Chart --}}
                    <div class="col-lg-8">
                        <div class="card rounded-4 border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title mb-4"><i class="fa fa-chart-line text-primary"></i> Monthly Earnings</h5>
                                <div class="chart-container" style="height: 320px; min-height: 280px; max-height: 360px;">
                                    <canvas id="vendorMonthlySalesChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Order Status Distribution --}}
                    <div class="col-lg-4">
                        <div class="card rounded-4 border-0 shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title mb-4"><i class="fa fa-chart-pie text-success"></i> Payment Methods</h5>
                                <div class="chart-container" style="height: 320px; min-height: 280px; max-height: 360px;">
                                    <canvas id="vendorPaymentMethodsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Top Products --}}
                @if(isset($top_products) && $top_products->count() > 0)
                <div class="row g-4 mt-3">
                    <div class="col-12">
                        <div class="card rounded-4 border-0 shadow-sm">
                            <div class="card-header bg-white border-0 pt-4">
                                <h5 class="mb-0"><i class="fa fa-star text-warning"></i> Top Selling Products</h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>SKU</th>
                                                <th>Product Name</th>
                                                <th>Price</th>
                                                <th>Sales Count</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($top_products as $product)
                                            <tr>
                                                <td><code>{{ $product->sku }}</code></td>
                                                <td>{{ $product->product_name_en }}</td>
                                                <td>${{ number_format($product->product_price, 2) }}</td>
                                                <td><span class="badge bg-success">{{ $product->sales_count ?? 0 }}</span></td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Recent Orders --}}
                @if(isset($recent_orders) && $recent_orders->count() > 0)
                <div class="row g-4 mt-3">
                    <div class="col-12">
                        <div class="card rounded-4 border-0 shadow-sm">
                            <div class="card-header bg-white border-0 pt-4">
                                <h5 class="mb-0"><i class="fa fa-receipt text-primary"></i> Recent Orders</h5>
                                <small class="text-muted">Track your latest sales</small>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>Order Number</th>
                                                <th>Customer</th>
                                                <th>Products</th>
                                                <th>Total</th>
                                                <th>Status</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($recent_orders as $order)
                                            <tr>
                                                <td><strong>{{ $order->order_number ?? 'N/A' }}</strong></td>
                                                <td>{{ $order->user ? $order->user->name : ($order->name ?? 'Guest Customer') }}</td>
                                                <td>{{ $order->items->count() }} item(s)</td>
                                                <td>${{ number_format($order->total, 2) }}</td>
                                                <td>
                                                    @if($order->status == 'completed')
                                                        <span class="badge bg-success">Completed</span>
                                                    @elseif($order->status == 'pending')
                                                        <span class="badge bg-warning">Pending</span>
                                                    @else
                                                        <span class="badge bg-danger">Cancelled</span>
                                                    @endif
                                                </td>
                                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @push('scripts')
                <script>
                    // Monthly Sales Chart
                    const vendorSalesCtx = document.getElementById('vendorMonthlySalesChart').getContext('2d');
                    new Chart(vendorSalesCtx, {
                        type: 'line',
                        data: {
                            labels: {!! json_encode(collect($monthly_sales_data ?? [])->pluck('month')) !!},
                            datasets: [{
                                label: 'Earnings ($)',
                                data: {!! json_encode(collect($monthly_sales_data ?? [])->pluck('earnings')) !!},
                                borderColor: '#02767F',
                                backgroundColor: 'rgba(2, 118, 127, 0.1)',
                                tension: 0.4,
                                fill: true
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        callback: function(value) {
                                            return '$' + value.toFixed(2);
                                        }
                                    }
                                }
                            }
                        }
                    });

                    // Payment Methods Chart
                    const vendorPaymentCtx = document.getElementById('vendorPaymentMethodsChart').getContext('2d');
                    new Chart(vendorPaymentCtx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Cash Orders', 'Card Orders'],
                            datasets: [{
                                data: [{{ $order__cash_only ?? 0 }}, {{ $order__card_only ?? 0 }}],
                                backgroundColor: ['#36a2eb', '#4bc0c0'],
                                borderWidth: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                }
                            }
                        }
                    });
                </script>
                @endpush
            @endif


        </div>
    </main>
@endsection
