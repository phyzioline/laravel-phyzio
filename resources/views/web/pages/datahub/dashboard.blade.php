@extends('web.layouts.app')

@section('title', 'Global PT Landscape | Data Hub')

@section('content')
<div class="container-fluid py-5" style="background-color: #f8f9fa;">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-5">
            <div>
                <h1 class="font-weight-bold text-dark">Global PT Landscape</h1>
                <p class="text-muted">Interactive analytics of Physical Therapy resources worldwide.</p>
            </div>
            <a href="{{ route('web.datahub.index') }}" class="btn btn-outline-secondary"><i class="las la-arrow-left"></i> Back to Hub</a>
        </div>

        <!-- Filters -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">
                    <div class="card-body p-3 d-flex align-items-center flex-wrap">
                        <span class="mr-3 font-weight-bold"><i class="las la-filter"></i> Filters:</span>
                        <div class="btn-group mr-3" role="group">
                            <button type="button" class="btn btn-outline-primary active">All Regions</button>
                            <button type="button" class="btn btn-outline-primary">North America</button>
                            <button type="button" class="btn btn-outline-primary">Europe</button>
                            <button type="button" class="btn btn-outline-primary">Asia</button>
                             <button type="button" class="btn btn-outline-primary">MENA</button>
                        </div>
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-primary">Therapists Count</button>
                            <button type="button" class="btn btn-outline-secondary">Population Ratio</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="card shadow border-0 h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="font-weight-bold">Top 20 Countries by Registered Therapists</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="globalChart"></canvas>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 mb-4">
                <div class="card shadow border-0 h-100 bg-primary text-white">
                    <div class="card-body p-4">
                        <h4 class="font-weight-bold mb-4"><i class="las la-info-circle"></i> Country Insights</h4>
                        <div id="country-details">
                            <h2 class="display-4 font-weight-bold">USA</h2>
                            <p class="lead">United States</p>
                            <hr class="border-white opacity-50">
                            <div class="mb-3">
                                <small>Total Therapists</small>
                                <h3>230,000+</h3>
                            </div>
                            <div class="mb-3">
                                <small>Population</small>
                                <h3>331 Million</h3>
                            </div>
                            <div class="mb-3">
                                <small>Therapist Ratio</small>
                                <h3>70 per 100k</h3>
                            </div>
                            <button class="btn btn-light btn-block mt-4 text-primary font-weight-bold">View Full Profile</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var ctx = document.getElementById('globalChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['USA', 'Brazil', 'germany', 'UK', 'India', 'Australia', 'Canada', 'Spain', 'France', 'Egypt', 'Italy', 'Netherlands', 'Japan', 'China', 'Saudi Arabia', 'UAE'],
            datasets: [{
                label: '# of Registered Therapists',
                data: [230000, 180000, 150000, 60000, 75000, 35000, 28000, 55000, 85000, 45000, 60000, 22000, 120000, 90000, 12000, 5000],
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
@endpush
