@extends('dashboard.layouts.app')
@section('content')
    <main class="page-wrapper">
        <div class="main-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">PHYZIOLINE | Dashboard</div>
            </div>



            @if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('super-admin'))
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                    <h5 class="mb-0">
                                        <i class="fa fa-user text-primary"></i> {{ __('Number of User') }}
                                    </h5>
                                </div>
                                <h2 class="mt-4 fw-bold">{{ $user ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                    <h5 class="mb-0">
                                        <i class="fa fa-store text-primary"></i> {{ __('Number of Vendor')  }}
                                    </h5>
                                </div>
                                <h2 class="mt-4 fw-bold">{{ $vendor ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                    <h5 class="mb-0">
                                        <i class="fa fa-cart-shopping text-primary"></i> {{ __('Number of Buyer')  }}
                                    </h5>
                                </div>
                                <h2 class="mt-4 fw-bold">{{ $buyer ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                    <h5 class="mb-0">
                                        <i class="fa fa-box text-primary"></i> {{ __('Number of Product')  }}
                                    </h5>
                                </div>
                                <h2 class="mt-4 fw-bold">{{ $product ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                    <h5 class="mb-0">
                                        <i class="fa fa-receipt text-primary"></i> {{ __('Number of Order')  }}
                                    </h5>
                                </div>
                                <h2 class="mt-4 fw-bold">{{ $order ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                    <h5 class="mb-0">
                                        <i class="fa fa-receipt text-primary"></i> {{ __('Number of Order Card')  }}
                                    </h5>
                                </div>
                                <h2 class="mt-4 fw-bold">{{ $order_card ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                    <h5 class="mb-0">
                                        <i class="fa fa-receipt text-primary"></i> {{ __('Number of Order Cash')  }}
                                    </h5>
                                </div>
                                <h2 class="mt-4 fw-bold">{{ $order_cash ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>

                  

                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                    <h5 class="mb-0">
                                        <i class="fa fa-tags text-primary"></i> {{ __('Number of Tag')  }}
                                    </h5>
                                </div>
                                <h2 class="mt-4 fw-bold">{{ $tag ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>

                    {{-- Ecosystem Stats --}}
                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                    <h5 class="mb-0">
                                        <i class="fa fa-user-md text-success"></i> {{ __('Therapists')  }}
                                    </h5>
                                </div>
                                <h2 class="mt-4 fw-bold">{{ $therapist_count ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                    <h5 class="mb-0">
                                        <i class="fa fa-clinic-medical text-info"></i> {{ __('Clinics')  }}
                                    </h5>
                                </div>
                                <h2 class="mt-4 fw-bold">{{ $clinic_count ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                    <h5 class="mb-0">
                                        <i class="fa fa-calendar-check text-warning"></i> {{ __('Home Visits')  }}
                                    </h5>
                                </div>
                                <h2 class="mt-4 fw-bold">{{ $appointment_count ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 col-sm-12">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                    <h5 class="mb-0">
                                        <i class="fa fa-graduation-cap text-danger"></i> {{ __('Courses')  }}
                                    </h5>
                                </div>
                                <h2 class="mt-4 fw-bold">{{ $course_count ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- Charts Section --}}
                <div class="row g-4 mt-4">
                    {{-- Payment Methods Chart --}}
                    <div class="col-lg-6">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <h5 class="card-title mb-4">Payment Methods Distribution</h5>
                                <div style="position: relative; height: 300px;">
                                    <canvas id="paymentMethodsChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- User Types Chart --}}
                    <div class="col-lg-6">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <h5 class="card-title mb-4">User Distribution</h5>
                                <div style="position: relative; height: 300px;">
                                    <canvas id="userTypesChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Ecosystem Overview Chart --}}
                    <div class="col-lg-12">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <h5 class="card-title mb-4">Ecosystem Overview</h5>
                                <div style="position: relative; height: 300px;">
                                    <canvas id="ecosystemChart"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- System Health Indicators --}}
                <div class="row g-4 mt-4">
                    <div class="col-12">
                        <h5 class="mb-3"><i class="fa fa-heartbeat text-danger"></i> System Health</h5>
                    </div>
                    
                    <div class="col-lg-3 col-md-6">
                        <div class="card rounded-4 border-0 shadow-sm" style="border-left: 4px solid #28a745 !important;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1 small">Total Revenue</p>
                                        <h3 class="mb-0 fw-bold">${{ number_format($totalRevenue, 2) }}</h3>
                                    </div>
                                    <i class="fa fa-dollar-sign fa-2x text-success opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="card rounded-4 border-0 shadow-sm" style="border-left: 4px solid #ffc107 !important;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1 small">Today's Home Visits</p>
                                        <h3 class="mb-0 fw-bold">{{ $todayAppointments }}</h3>
                                    </div>
                                    <i class="fa fa-calendar-day fa-2x text-warning opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="card rounded-4 border-0 shadow-sm" style="border-left: 4px solid #17a2b8 !important;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1 small">Active Platform</p>
                                        <h3 class="mb-0 fw-bold text-success">Online</h3>
                                    </div>
                                    <i class="fa fa-check-circle fa-2x text-info opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="card rounded-4 border-0 shadow-sm" style="border-left: 4px solid #6c757d !important;">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <p class="text-muted mb-1 small">System Status</p>
                                        <h3 class="mb-0 fw-bold text-muted">Stable</h3>
                                    </div>
                                    <i class="fa fa-server fa-2x text-secondary opacity-50"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick Access Cards --}}
                <div class="row g-4 mt-4">
                    <div class="col-12">
                        <h5 class="mb-3"><i class="fa fa-bolt text-warning"></i> Quick Access</h5>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <a href="{{ route('dashboard.users.index') }}" class="text-decoration-none">
                            <div class="card rounded-4 border-0 shadow-sm hover-lift h-100">
                                <div class="card-body text-center py-4">
                                    <i class="fa fa-users fa-3x mb-3" style="color: #02767F;"></i>
                                    <h6 class="fw-bold mb-1">Manage Users</h6>
                                    <small class="text-muted">View & edit all users</small>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <a href="{{ route('dashboard.therapist_profiles.index') }}" class="text-decoration-none">
                            <div class="card rounded-4 border-0 shadow-sm hover-lift h-100">
                                <div class="card-body text-center py-4">
                                    <i class="fa fa-user-md fa-3x mb-3 text-success"></i>
                                    <h6 class="fw-bold mb-1">Manage Therapists</h6>
                                    <small class="text-muted">Approve & manage</small>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <a href="{{ route('dashboard.home_visits.index') }}" class="text-decoration-none">
                            <div class="card rounded-4 border-0 shadow-sm hover-lift h-100">
                                <div class="card-body text-center py-4">
                                    <i class="fa fa-calendar-check fa-3x mb-3 text-warning"></i>
                                    <h6 class="fw-bold mb-1">Home Visits</h6>
                                    <small class="text-muted">View all bookings</small>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <a href="{{ route('dashboard.clinic_profiles.index') }}" class="text-decoration-none">
                            <div class="card rounded-4 border-0 shadow-sm hover-lift h-100">
                                <div class="card-body text-center py-4">
                                    <i class="fa fa-clinic-medical fa-3x mb-3 text-info"></i>
                                    <h6 class="fw-bold mb-1">Manage Clinics</h6>
                                    <small class="text-muted">Clinic profiles</small>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <a href="{{ route('dashboard.courses.index') }}" class="text-decoration-none">
                            <div class="card rounded-4 border-0 shadow-sm hover-lift h-100">
                                <div class="card-body text-center py-4">
                                    <i class="fa fa-graduation-cap fa-3x mb-3 text-danger"></i>
                                    <h6 class="fw-bold mb-1">Manage Courses</h6>
                                    <small class="text-muted">Learning hub</small>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <a href="{{ route('dashboard.products.index') }}" class="text-decoration-none">
                            <div class="card rounded-4 border-0 shadow-sm hover-lift h-100">
                                <div class="card-body text-center py-4">
                                    <i class="fa fa-box fa-3x mb-3" style="color: #02767F;"></i>
                                    <h6 class="fw-bold mb-1">Products</h6>
                                    <small class="text-muted">Inventory management</small>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <a href="{{ route('dashboard.orders.index') }}" class="text-decoration-none">
                            <div class="card rounded-4 border-0 shadow-sm hover-lift h-100">
                                <div class="card-body text-center py-4">
                                    <i class="fa fa-receipt fa-3x mb-3 text-success"></i>
                                    <h6 class="fw-bold mb-1">Orders</h6>
                                    <small class="text-muted">View all orders</small>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <a href="{{ route('dashboard.settings.show') }}" class="text-decoration-none">
                            <div class="card rounded-4 border-0 shadow-sm hover-lift h-100">
                                <div class="card-body text-center py-4">
                                    <i class="fa fa-cog fa-3x mb-3 text-secondary"></i>
                                    <h6 class="fw-bold mb-1">Settings</h6>
                                    <small class="text-muted">System configuration</small>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

                {{-- Pending Approvals --}}
                @if(collect($pendingApprovals)->sum('count') > 0)
                <div class="row g-4 mt-4">
                    <div class="col-12">
                        <h5 class="mb-3"><i class="fa fa-clock text-warning"></i> Pending Approvals</h5>
                    </div>

                    @foreach($pendingApprovals as $pending)
                        @if($pending['count'] > 0)
                        <div class="col-lg-4 col-md-6">
                            <div class="card rounded-4 border-0 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1">{{ $pending['title'] }}</h6>
                                            <p class="text-muted mb-0 small">Requires attention</p>
                                        </div>
                                        <div class="text-center">
                                            <span class="badge bg-{{ $pending['color'] }} rounded-circle p-3" style="font-size: 1.2rem;">
                                                {{ $pending['count'] }}
                                            </span>
                                        </div>
                                    </div>
                                    <a href="{{ $pending['link'] }}" class="btn btn-sm btn-outline-{{ $pending['color'] }} w-100 mt-3">
                                        <i class="fa {{ $pending['icon'] }}"></i> Review Now
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
                            <div class="card-header bg-white border-0 pt-4">
                                <h5 class="mb-0"><i class="fa fa-stream text-primary"></i> Recent Activity</h5>
                                <small class="text-muted">Latest platform actions</small>
                            </div>
                            <div class="card-body">
                                @if($recentActivity && count($recentActivity) > 0)
                                    <div class="activity-feed">
                                        @foreach($recentActivity as $activity)
                                        <div class="activity-item d-flex align-items-start mb-3 pb-3 border-bottom">
                                            <div class="activity-icon me-3">
                                                <span class="badge bg-{{ $activity['color'] }} rounded-circle p-2">
                                                    <i class="fa {{ $activity['icon'] }}"></i>
                                                </span>
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="mb-1 fw-bold">{{ $activity['title'] }}</h6>
                                                <p class="mb-1 text-muted small">{{ $activity['description'] }}</p>
                                                <small class="text-muted">
                                                    <i class="fa fa-clock"></i> {{ $activity['time']->diffForHumans() }}
                                                </small>
                                            </div>
                                            <a href="{{ $activity['link'] }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fa fa-arrow-right"></i>
                                            </a>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <div class="text-center py-5">
                                        <i class="fa fa-inbox fa-3x text-muted mb-3 opacity-50"></i>
                                        <p class="text-muted">No recent activity</p>
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

                    // Ecosystem Overview Chart
                    const ecosystemCtx = document.getElementById('ecosystemChart').getContext('2d');
                    new Chart(ecosystemCtx, {
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
                                <div style="position: relative; height: 250px;">
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
                                <div style="position: relative; height: 250px;">
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
                                                <td>{{ $order->user->name }}</td>
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
