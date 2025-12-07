@extends('dashboard.layouts.app')
@section('title', __('Traffic Reports'))

@section('content')
<div class="main-wrapper">
    <div class="main-content">
        <div class="card">
            <div class="card-body text-center p-5">
                <i class="bi bi-bar-chart-line display-1 text-primary"></i>
                <h3 class="mt-3">Traffic Analytics</h3>
                <p class="text-muted">Detailed traffic analytics integration (Google Analytics or similar) coming soon.</p>
                <div class="row justify-content-center mt-5">
                    <div class="col-md-3">
                        <div class="card border">
                            <div class="card-body">
                                <h6>Page Views</h6>
                                <h4>0</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card border">
                            <div class="card-body">
                                <h6>Unique Visitors</h6>
                                <h4>0</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
