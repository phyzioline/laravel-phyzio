@extends('web.layouts.app')

@section('title', 'Global Physio Data Hub | PhyzioLine')

@section('content')
<main>
    <!-- Hero Section -->
    <section class="hero-section position-relative pt-150 pb-100 text-white" style="background-color: #02767F; margin-top: 80px;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 mx-auto text-center">
                    <span class="text-uppercase tracking-wider mb-2 d-block" style="color: #fff; letter-spacing: 2px;">Knowledge Center</span>
                    <h1 class="mb-4 display-4 font-weight-bold">Global Physiotherapy Data Hub</h1>
                    <p class="lead mb-5 text-white-50">Comprehensive data, market insights, and licensing requirements for physiotherapists worldwide.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Grid -->
    <section class="py-5" style="background-color: #f8f9fa;">
        <div class="container">
            <div class="row mt-n5">
                <div class="col-md-3 mb-4">
                    <div class="card border-0 shadow-sm text-center py-4">
                        <h2 class="display-4 font-weight-bold text-primary mb-0" style="color: #04b8c4;">120+</h2>
                        <p class="text-muted font-weight-bold">Countries Covered</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card border-0 shadow-sm text-center py-4">
                        <h2 class="display-4 font-weight-bold text-primary mb-0" style="color: #04b8c4;">50k+</h2>
                        <p class="text-muted font-weight-bold">Clinics Listed</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card border-0 shadow-sm text-center py-4">
                        <h2 class="display-4 font-weight-bold text-primary mb-0" style="color: #04b8c4;">$75k</h2>
                        <p class="text-muted font-weight-bold">Avg. Global Salary</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="card border-0 shadow-sm text-center py-4">
                        <h2 class="display-4 font-weight-bold text-primary mb-0" style="color: #04b8c4;">24/7</h2>
                        <p class="text-muted font-weight-bold">Data Updates</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Content Sections -->
    <section class="py-100">
        <div class="container">
            <div class="row">
                <!-- Salary Data -->
                <div class="col-lg-6 mb-5">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-5">
                            <div class="mb-4">
                                <i class="las la-money-bill-wave" style="font-size: 48px; color: #04b8c4;"></i>
                            </div>
                            <h3 class="font-weight-bold mb-3">Salary Insights</h3>
                            <p class="text-muted mb-4">Compare physiotherapy salaries across different countries, experience levels, and specializations.</p>
                            <ul class="list-unstyled mb-4">
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Average hourly & annual rates</li>
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Cost of living adjustments</li>
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Salary growth trends</li>
                            </ul>
                            <a href="#" class="btn btn-outline-primary">Explore Salaries</a>
                        </div>
                    </div>
                </div>

                <!-- Licensing Info -->
                <div class="col-lg-6 mb-5">
                    <div class="card border-0 shadow-sm h-100">
                        <div class="card-body p-5">
                            <div class="mb-4">
                                <i class="las la-passport" style="font-size: 48px; color: #04b8c4;"></i>
                            </div>
                            <h3 class="font-weight-bold mb-3">Licensing & Immigration</h3>
                            <p class="text-muted mb-4">Detailed guides on how to practice physiotherapy in other countries. Exam requirements and equivalency processes.</p>
                            <ul class="list-unstyled mb-4">
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Country-specific requirements</li>
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Exam preparation resources</li>
                                <li class="mb-2"><i class="las la-check text-success mr-2"></i> Visa & immigration guides</li>
                            </ul>
                            <a href="#" class="btn btn-outline-primary">View Licensing Guides</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection
