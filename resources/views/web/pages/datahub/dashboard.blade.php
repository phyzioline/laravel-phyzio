@extends('web.layouts.app')

@section('title', __('Global Physical Therapy Landscape Dashboard'))

@section('content')
<div class="dashboard-wrapper bg-light py-4">
    <div class="container">
        <!-- Header -->
        <div class="row mb-4 text-center">
            <div class="col-12">
                <h2 class="text-teal-700 font-weight-bold" style="color: #0d9488;">{{ __('Global Physical Therapy Landscape') }}</h2>
                <p class="text-muted">{{ __('An interactive overview of physical therapy resources, platforms, and rehabilitation centers across the globe with focus on Arab countries.') }}</p>
            </div>
        </div>

        <!-- Controls Section -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body p-4">
                <div class="row align-items-center">
                    <div class="col-md-3 mb-3 mb-md-0">
                        <label class="font-weight-bold text-teal-600 mb-2" style="color: #0d9488;">{{ __('Filter by Continent') }}</label>
                        <select id="continentFilter" class="form-control rounded-pill border-teal" style="border-color: #0d9488;">
                            <option value="all">{{ __('All Continents') }}</option>
                            <option value="africa">{{ __('Africa') }}</option>
                            <option value="asia">{{ __('Asia') }}</option>
                            <option value="europe">{{ __('Europe') }}</option>
                            <option value="north_america">{{ __('North America') }}</option>
                            <option value="south_america">{{ __('South America') }}</option>
                            <option value="oceania">{{ __('Oceania') }}</option>
                        </select>
                    </div>

                    <div class="col-md-9">
                        <label class="font-weight-bold text-teal-600 mb-2 d-block" style="color: #0d9488;">{{ __('Select a Metric to Visualize') }}</label>
                        <div class="btn-group w-100 flex-wrap" role="group">
                            <button type="button" class="btn btn-outline-teal metric-btn active rounded-pill mr-2 mb-2" data-metric="therapists" style="border-color:#0d9488; color: #0d9488;">
                                <i class="las la-user-nurse mr-1"></i> {{ __('Therapists') }}
                            </button>
                            <button type="button" class="btn btn-outline-teal metric-btn rounded-pill mr-2 mb-2" data-metric="population" style="border-color:#0d9488; color: #0d9488;">
                                <i class="las la-users mr-1"></i> {{ __('Population') }}
                            </button>
                            <button type="button" class="btn btn-outline-teal metric-btn rounded-pill mr-2 mb-2" data-metric="schools" style="border-color:#0d9488; color: #0d9488;">
                                <i class="las la-university mr-1"></i> {{ __('Schools/Colleges') }}
                            </button>
                            <button type="button" class="btn btn-outline-teal metric-btn rounded-pill mb-2" data-metric="centers" style="border-color:#0d9488; color: #0d9488;">
                                <i class="las la-hospital-alt mr-1"></i> {{ __('Rehab Centers') }}
                            </button>
                        </div>
                        <div class="text-right mt-2">
                             <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="digitalPlatformsToggle">
                                <label class="custom-control-label" for="digitalPlatformsToggle">{{ __('Digital Platforms') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h5 class="card-title text-center mb-4 text-teal-700" id="chartTitle" style="color: #0d9488;">{{ __('Number of Registered Therapists (20 Countries)') }}</h5>
                <div style="position: relative; height: 500px; width: 100%;">
                    <canvas id="landscapeChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Country Detail Modal -->
<div class="modal fade" id="countryDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-teal text-white" style="background-color: #0d9488;">
                <h5 class="modal-title font-weight-bold" id="modalCountryName">Country Name</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body p-4">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="detail-item p-3 rounded bg-light">
                            <i class="las la-user-nurse text-teal-600 mb-2" style="font-size: 2rem; color: #0d9488;"></i>
                            <h6 class="text-muted mb-1">{{ __('Therapists') }}</h6>
                            <h4 class="font-weight-bold" id="modalTherapists">0</h4>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="detail-item p-3 rounded bg-light">
                            <i class="las la-users text-teal-600 mb-2" style="font-size: 2rem; color: #0d9488;"></i>
                            <h6 class="text-muted mb-1">{{ __('Population') }}</h6>
                            <h4 class="font-weight-bold" id="modalPopulation">0M</h4>
                        </div>
                    </div>
                </div>
                 <div class="row text-center">
                    <div class="col-6 mb-3">
                         <div class="detail-item p-3 rounded bg-light">
                            <i class="las la-university text-teal-600 mb-2" style="font-size: 2rem; color: #0d9488;"></i>
                            <h6 class="text-muted mb-1">{{ __('PT Schools') }}</h6>
                            <h4 class="font-weight-bold" id="modalSchools">0</h4>
                        </div>
                    </div>
                     <div class="col-6 mb-3">
                         <div class="detail-item p-3 rounded bg-light">
                            <i class="las la-hospital-alt text-teal-600 mb-2" style="font-size: 2rem; color: #0d9488;"></i>
                            <h6 class="text-muted mb-1">{{ __('Rehab Centers') }}</h6>
                            <h4 class="font-weight-bold" id="modalCenters">0</h4>
                        </div>
                    </div>
                </div>
                <div class="alert alert-info border-0 text-center mt-2" style="background-color: #e0f2f1; color: #00695c;">
                    <strong>{{ __('Therapist-to-Population Ratio') }}:</strong> <span id="modalRatio">0</span> {{ __('per 100k') }}
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Enhanced Mock Data (Global Coverage) ---
        // --- Global Physical Therapy Landscape Data (Estimated 2024) ---
        const rawData = [
            // North America
            { country: 'USA', continent: 'north_america', therapists: 247000, population: 331000000, schools: 275, centers: 18000 },
            { country: 'Canada', continent: 'north_america', therapists: 29000, population: 38000000, schools: 15, centers: 1600 },
            { country: 'Mexico', continent: 'north_america', therapists: 18000, population: 126000000, schools: 45, centers: 900 },
            
            // Europe
            { country: 'Germany', continent: 'europe', therapists: 195000, population: 83000000, schools: 100, centers: 9000 },
            { country: 'UK', continent: 'europe', therapists: 62000, population: 67000000, schools: 50, centers: 3500 },
            { country: 'France', continent: 'europe', therapists: 95000, population: 65000000, schools: 55, centers: 6500 },
            { country: 'Italy', continent: 'europe', therapists: 68000, population: 60000000, schools: 38, centers: 4200 },
            { country: 'Spain', continent: 'europe', therapists: 58000, population: 47000000, schools: 45, centers: 3800 },
            { country: 'Netherlands', continent: 'europe', therapists: 24000, population: 17000000, schools: 14, centers: 2000 },
            { country: 'Sweden', continent: 'europe', therapists: 15000, population: 10000000, schools: 9, centers: 1000 },
            { country: 'Belgium', continent: 'europe', therapists: 32000, population: 11500000, schools: 12, centers: 1500 },
            { country: 'Poland', continent: 'europe', therapists: 45000, population: 38000000, schools: 25, centers: 1800 },
            { country: 'Switzerland', continent: 'europe', therapists: 12000, population: 8600000, schools: 5, centers: 800 },
            { country: 'Norway', continent: 'europe', therapists: 11000, population: 5400000, schools: 4, centers: 600 },
            { country: 'Denmark', continent: 'europe', therapists: 10000, population: 5800000, schools: 5, centers: 550 },
            { country: 'Finland', continent: 'europe', therapists: 9000, population: 5500000, schools: 5, centers: 500 },
            { country: 'Ireland', continent: 'europe', therapists: 5000, population: 5000000, schools: 4, centers: 300 },
            { country: 'Portugal', continent: 'europe', therapists: 12000, population: 10000000, schools: 10, centers: 500 },
            { country: 'Greece', continent: 'europe', therapists: 8000, population: 10400000, schools: 6, centers: 400 },
            
            // Asia
            { country: 'Japan', continent: 'asia', therapists: 130000, population: 126000000, schools: 120, centers: 4500 },
            { country: 'China', continent: 'asia', therapists: 90000, population: 1400000000, schools: 90, centers: 4000 },
            { country: 'India', continent: 'asia', therapists: 110000, population: 1380000000, schools: 350, centers: 6000 },
            { country: 'South Korea', continent: 'asia', therapists: 35000, population: 51000000, schools: 50, centers: 1400 },
            { country: 'Saudi Arabia', continent: 'asia', therapists: 14000, population: 35000000, schools: 18, centers: 950 },
            { country: 'UAE', continent: 'asia', therapists: 6500, population: 9900000, schools: 6, centers: 450 },
            { country: 'Jordan', continent: 'asia', therapists: 7500, population: 10200000, schools: 10, centers: 300 },
            { country: 'Philippines', continent: 'asia', therapists: 22000, population: 110000000, schools: 40, centers: 700 },
            { country: 'Thailand', continent: 'asia', therapists: 10000, population: 70000000, schools: 14, centers: 500 },
            { country: 'Vietnam', continent: 'asia', therapists: 8000, population: 97000000, schools: 10, centers: 400 },
            { country: 'Indonesia', continent: 'asia', therapists: 15000, population: 273000000, schools: 20, centers: 600 },
            { country: 'Malaysia', continent: 'asia', therapists: 5000, population: 32000000, schools: 8, centers: 250 },
            { country: 'Singapore', continent: 'asia', therapists: 2500, population: 5700000, schools: 2, centers: 150 },
            { country: 'Turkey', continent: 'asia', therapists: 25000, population: 84000000, schools: 30, centers: 1200 },
            { country: 'Israel', continent: 'asia', therapists: 7000, population: 9000000, schools: 5, centers: 300 },
            { country: 'Taiwan', continent: 'asia', therapists: 8000, population: 23000000, schools: 10, centers: 350 },
            
            // South America
            { country: 'Brazil', continent: 'south_america', therapists: 280000, population: 212000000, schools: 250, centers: 7000 },
            { country: 'Argentina', continent: 'south_america', therapists: 40000, population: 45000000, schools: 30, centers: 1400 },
            { country: 'Colombia', continent: 'south_america', therapists: 15000, population: 50000000, schools: 22, centers: 850 },
            { country: 'Chile', continent: 'south_america', therapists: 18000, population: 19000000, schools: 20, centers: 600 },
            { country: 'Peru', continent: 'south_america', therapists: 9000, population: 33000000, schools: 12, centers: 400 },
            { country: 'Venezuela', continent: 'south_america', therapists: 6000, population: 28000000, schools: 8, centers: 300 },
            
            // Africa
            { country: 'Egypt', continent: 'africa', therapists: 55000, population: 102000000, schools: 30, centers: 1500 },
            { country: 'South Africa', continent: 'africa', therapists: 9500, population: 59000000, schools: 15, centers: 600 },
            { country: 'Nigeria', continent: 'africa', therapists: 6000, population: 206000000, schools: 10, centers: 250 },
            { country: 'Morocco', continent: 'africa', therapists: 9000, population: 37000000, schools: 7, centers: 500 },
            { country: 'Kenya', continent: 'africa', therapists: 3000, population: 53000000, schools: 5, centers: 150 },
            { country: 'Ghana', continent: 'africa', therapists: 1500, population: 31000000, schools: 3, centers: 100 },
            { country: 'Ethiopia', continent: 'africa', therapists: 2000, population: 115000000, schools: 4, centers: 100 },
            { country: 'Algeria', continent: 'africa', therapists: 7000, population: 43000000, schools: 6, centers: 300 },
            
            // Oceania
            { country: 'Australia', continent: 'oceania', therapists: 38000, population: 25000000, schools: 25, centers: 2000 },
            { country: 'New Zealand', continent: 'oceania', therapists: 7000, population: 5000000, schools: 5, centers: 450 }
        ];

        let chartInstance = null;
        let currentMetric = 'therapists';

        const ctx = document.getElementById('landscapeChart').getContext('2d');

        // Color Palettes
        const colors = {
            therapists: { bg: 'rgba(13, 148, 136, 0.7)', border: '#0d9488' },
            population: { bg: 'rgba(59, 130, 246, 0.7)', border: '#3b82f6' },
            schools: { bg: 'rgba(245, 158, 11, 0.7)', border: '#f59e0b' },
            centers: { bg: 'rgba(16, 185, 129, 0.7)', border: '#10b981' }
        };

        function initChart(data) {
            // Sort data by current metric desc
            data.sort((a, b) => b[currentMetric] - a[currentMetric]);
            const topCountries = data.slice(0, 20); // Top 20

            const labels = topCountries.map(d => d.country);
            const values = topCountries.map(d => d[currentMetric]);

            if (chartInstance) {
                chartInstance.destroy();
            }

            // Using Chart.js 3.x / 4.x Syntax
            chartInstance = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: '{{ __('Count') }}', // This might need escaping if handled by blade
                        data: values,
                        backgroundColor: colors[currentMetric].bg,
                        borderColor: colors[currentMetric].border,
                        borderWidth: 1,
                        hoverBackgroundColor: colors[currentMetric].border
                    }]
                },
                options: {
                    indexAxis: 'y', // Horizontal Bar Chart in Chart.js 3+
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { // 3.x Namespacing
                        legend: { display: false },
                        title: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) {
                                        label += ': ';
                                    }
                                    if (context.parsed.x !== null) {
                                        label += context.parsed.x.toLocaleString();
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: { // 3.x Scale ID
                            beginAtZero: true
                        }
                    },
                    onClick: (e, elements) => {
                        if (elements.length > 0) {
                            const index = elements[0].index; // 3.x element index
                            const countryData = topCountries[index];
                            showCountryDetail(countryData);
                        }
                    } 
                }
            });
        }

        function showCountryDetail(data) {
            document.getElementById('modalCountryName').innerText = data.country;
            document.getElementById('modalTherapists').innerText = data.therapists.toLocaleString();
            document.getElementById('modalPopulation').innerText = (data.population / 1000000).toFixed(1) + 'M';
            document.getElementById('modalSchools').innerText = data.schools;
            document.getElementById('modalCenters').innerText = data.centers;
            
            const ratio = ((data.therapists / data.population) * 100000).toFixed(1);
            document.getElementById('modalRatio').innerText = ratio;

            $('#countryDetailModal').modal('show');
        }

        function updateChart() {
            const continent = document.getElementById('continentFilter').value;
            let filteredData = rawData;

            if (continent !== 'all') {
                filteredData = rawData.filter(d => d.continent === continent);
            }

            initChart(filteredData);
            
            // Update Title
            const metricText = document.querySelector(`.metric-btn[data-metric="${currentMetric}"]`).innerText;
            document.getElementById('chartTitle').innerText = `{{ __('Top Countries by') }} ${metricText}`;
        }

        // Event Listeners
        document.getElementById('continentFilter').addEventListener('change', updateChart);
        
        document.querySelectorAll('.metric-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove active class
                document.querySelectorAll('.metric-btn').forEach(b => {
                    b.classList.remove('active', 'btn-teal', 'text-white');
                    b.classList.add('btn-outline-teal');
                    b.style.backgroundColor = 'transparent';
                    b.style.color = '#0d9488';
                });
                
                // Add active class
                this.classList.remove('btn-outline-teal');
                this.classList.add('active', 'btn-teal', 'text-white');
                this.style.backgroundColor = '#0d9488';
                this.style.color = '#fff';

                currentMetric = this.getAttribute('data-metric');
                updateChart();
            });
        });

        // Initialize with better default styling safety
        const activeBtn = document.querySelector('.metric-btn.active');
        if(activeBtn) {
            activeBtn.classList.remove('btn-outline-teal');
            activeBtn.classList.add('btn-teal', 'text-white');
            activeBtn.style.backgroundColor = '#0d9488';
            activeBtn.style.color = '#fff';
        }

        initChart(rawData);
    });
</script>
@endpush

<style>
    .btn-teal {
        background-color: #0d9488;
        color: white;
    }
    .btn-outline-teal {
        border-color: #0d9488;
        color: #0d9488;
        background-color: transparent;
    }
    .btn-outline-teal:hover {
        background-color: #0d9488;
        color: white;
    }
    .text-teal-600 { color: #0d9488 !important; }
    .text-teal-700 { color: #0f766e !important; }
</style>
@endsection
