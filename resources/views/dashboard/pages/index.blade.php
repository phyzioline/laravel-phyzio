@extends('dashboard.layouts.app')
@section('content')
    <main class="main-wrapper">
        <div class="main-content">
            <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
                <div class="breadcrumb-title pe-3">PHYZIOLINE | Dashboard</div>
            </div>



            @if (auth()->user()->hasRole('admin'))
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
