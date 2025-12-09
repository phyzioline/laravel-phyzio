@extends('dashboard.layouts.app')
@section('content')
    <main class="main-wrapper">
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
                                        <i class="fa fa-calendar-check text-warning"></i> {{ __('Appointments')  }}
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
                                <canvas id="paymentMethodsChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- User Types Chart --}}
                    <div class="col-lg-6">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <h5 class="card-title mb-4">User Distribution</h5>
                                <canvas id="userTypesChart" height="300"></canvas>
                            </div>
                        </div>
                    </div>

                    {{-- Ecosystem Overview Chart --}}
                    <div class="col-lg-12">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <h5 class="card-title mb-4">Ecosystem Overview</h5>
                                <canvas id="ecosystemChart" height="120"></canvas>
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
                                        <p class="text-muted mb-1 small">Today's Appointments</p>
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
                        <a href="{{ route('dashboard.appointments.index') }}" class="text-decoration-none">
                            <div class="card rounded-4 border-0 shadow-sm hover-lift h-100">
                                <div class="card-body text-center py-4">
                                    <i class="fa fa-calendar-check fa-3x mb-3 text-warning"></i>
                                    <h6 class="fw-bold mb-1">Appointments</h6>
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
            @endif
            @if (auth()->user()->hasRole('vendor'))
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                    <h5 class="mb-0">
                                        <i class="fa fa-tags text-primary"></i> {{ __('Number of Product') }}
                                    </h5>
                                </div>
                                <h2 class="mt-4 fw-bold">{{ $product_only ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                      <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                    <h5 class="mb-0">
                                        <i class="fa fa-tags text-primary"></i> {{ __('Number of Order')  }}
                                    </h5>
                                </div>
                                <h2 class="mt-4 fw-bold">{{ $order_only ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                    <h5 class="mb-0">
                                        <i class="fa fa-tags text-primary"></i> {{ __('Number of Order Card')  }}
                                    </h5>
                                </div>
                                <h2 class="mt-4 fw-bold">{{ $order__card_only ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-4 col-md-6 col-sm-12">
                        <div class="card rounded-4">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center gap-3 mb-2">
                                    <h5 class="mb-0">
                                        <i class="fa fa-tags text-primary"></i> {{ __('Number of Order Cash')  }}
                                    </h5>
                                </div>
                                <h2 class="mt-4 fw-bold">{{ $order__cash_only ?? 0 }}</h2>
                            </div>
                        </div>
                    </div>
                </div>
            @endif


        </div>
    </main>
@endsection
