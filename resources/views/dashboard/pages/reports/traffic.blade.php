@extends('dashboard.layouts.app')
@section('title', __('Traffic Reports'))

@section('content')
<div class="main-wrapper">
    <div class="main-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="breadcrumb-title pe-3">{{ __('Traffic Reports') }}</div>
        </div>

        <div class="row g-4">
            <!-- Traffic Stats Cards -->
            <div class="col-lg-3 col-md-6">
                <div class="card rounded-3 border-0 shadow-sm">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">{{ __('Total Users') }}</p>
                                <h5 class="mb-0 fw-bold">{{ number_format($pageViews ?? 0) }}</h5>
                            </div>
                            <i class="fa fa-users fa-lg text-primary opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card rounded-3 border-0 shadow-sm">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">{{ __('Active Users (30 Days)') }}</p>
                                <h5 class="mb-0 fw-bold">{{ number_format($activeUsersLast30Days ?? 0) }}</h5>
                            </div>
                            <i class="fa fa-user-check fa-lg text-success opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card rounded-3 border-0 shadow-sm">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">{{ __('Unique Visitors') }}</p>
                                <h5 class="mb-0 fw-bold">{{ number_format($uniqueVisitors ?? 0) }}</h5>
                            </div>
                            <i class="fa fa-eye fa-lg text-info opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6">
                <div class="card rounded-3 border-0 shadow-sm">
                    <div class="card-body p-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <p class="text-muted mb-1 small">{{ __('New Users (7 Days)') }}</p>
                                <h5 class="mb-0 fw-bold">{{ number_format($newUsersLast7Days ?? 0) }}</h5>
                            </div>
                            <i class="fa fa-user-plus fa-lg text-warning opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Top Pages Section -->
            @if(isset($topPages) && $topPages->count() > 0)
            <div class="col-12">
                <div class="card rounded-3 border-0 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">{{ __('Top Activity Areas') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ __('Activity Type') }}</th>
                                        <th class="text-end">{{ __('Count') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topPages as $page)
                                    <tr>
                                        <td>{{ $page['name'] }}</td>
                                        <td class="text-end"><strong>{{ number_format($page['count']) }}</strong></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="col-12">
                <div class="card rounded-3 border-0 shadow-sm">
                    <div class="card-body text-center p-5">
                        <i class="bi bi-bar-chart-line display-1 text-muted opacity-50"></i>
                        <h5 class="mt-3">{{ __('No Traffic Data Available') }}</h5>
                        <p class="text-muted">{{ __('Traffic data will appear here as users interact with the platform.') }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
