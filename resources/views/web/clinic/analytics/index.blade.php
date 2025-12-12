@extends('web.layouts.dashboard_master')

@section('title', 'Analytics')
@section('header_title', 'Performance Analytics')

@section('content')
<div class="row">
    <!-- Revenue Chart -->
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="font-weight-bold mb-0">Revenue Overview</h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="150"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Growth Stats -->
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="font-weight-bold mb-0">Patient Growth</h5>
            </div>
             <div class="card-body">
                <canvas id="growthChart" height="200"></canvas>
                <div class="mt-4 text-center">
                    <h3 class="font-weight-bold text-success">+15%</h3>
                    <p class="text-muted">Growth in new patients this quarter</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Revenue Chart
    var ctxR = document.getElementById('revenueChart').getContext('2d');
    new Chart(ctxR, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
            datasets: [{
                label: 'Revenue (EGP)',
                data: [12000, 15000, 18000, 22000, 25000, 24000],
                backgroundColor: '#00897b'
            }]
        }
    });

    // Growth Chart
    var ctxG = document.getElementById('growthChart').getContext('2d');
    new Chart(ctxG, {
        type: 'doughnut',
        data: {
            labels: ['New', 'Returning'],
            datasets: [{
                data: [35, 65],
                backgroundColor: ['#00897b', '#e0f2f1']
            }]
        }
    });
</script>
@endpush
