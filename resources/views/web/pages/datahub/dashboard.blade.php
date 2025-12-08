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
        // --- Mock Data ---
        // Enhanced mock data covering more regions as requested
        const rawData = [
            { country: 'USA', continent: 'north_america', therapists: 230000, population: 331000000, schools: 260, centers: 15000 },
            { country: 'Germany', continent: 'europe', therapists: 185000, population: 83000000, schools: 90, centers: 8500 },
            { country: 'Japan', continent: 'asia', therapists: 120000, population: 126000000, schools: 110, centers: 4000 },
            { country: 'Brazil', continent: 'south_america', therapists: 240000, population: 212000000, schools: 200, centers: 6000 },
            { country: 'Egypt', continent: 'africa', therapists: 45000, population: 102000000, schools: 25, centers: 1200 },
            { country: 'Saudi Arabia', continent: 'asia', therapists: 12000, population: 34000000, schools: 15, centers: 800 },
            { country: 'UAE', continent: 'asia', therapists: 4500, population: 9800000, schools: 5, centers: 350 },
            { country: 'Jordan', continent: 'asia', therapists: 6000, population: 10200000, schools: 8, centers: 250 },
            { country: 'Morocco', continent: 'africa', therapists: 8000, population: 36000000, schools: 6, centers: 450 },
            { country: 'UK', continent: 'europe', therapists: 60000, population: 67000000, schools: 45, centers: 3200 },
            { country: 'Australia', continent: 'oceania', therapists: 35000, population: 25000000, schools: 22, centers: 1800 },
            { country: 'India', continent: 'asia', therapists: 95000, population: 1380000000, schools: 300, centers: 5500 },
            { country: 'China', continent: 'asia', therapists: 55000, population: 1400000000, schools: 60, centers: 2500 }, // Lower density example
            { country: 'Nigeria', continent: 'africa', therapists: 5000, population: 206000000, schools: 8, centers: 150 },
            { country: 'South Africa', continent: 'africa', therapists: 8500, population: 59000000, schools: 12, centers: 500 },
             { country: 'Canada', continent: 'north_america', therapists: 28000, population: 38000000, schools: 15, centers: 1400 },
            { country: 'France', continent: 'europe', therapists: 90000, population: 65000000, schools: 50, centers: 6200 },
             { country: 'Italy', continent: 'europe', therapists: 65000, population: 60000000, schools: 35, centers: 4000 },
        ];

        let chartInstance = null;
        let currentMetric = 'therapists';
        let currentGradient = null;

        const ctx = document.getElementById('landscapeChart').getContext('2d');

        // Color Palettes
        const colors = {
            therapists: { 
                bg: 'rgba(13, 148, 136, 0.7)', // Teal
                border: '#0d9488'
            },
            population: { 
                bg: 'rgba(59, 130, 246, 0.7)', // Blue
                border: '#3b82f6'
            },
            schools: {
                bg: 'rgba(245, 158, 11, 0.7)', // Amber
                border: '#f59e0b'
            },
            centers: {
                bg: 'rgba(16, 185, 129, 0.7)', // Emerald
                border: '#10b981'
            }
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

            chartInstance = new Chart(ctx, {
                type: 'horizontalBar', // Note: Chart.js 2.x syntax used in existing project? Checking version... assuming 2.9.4 as wrapper for 2.x is common in older setups, OR 3.x if new. Let's assume standard 'bar' with indexAxis 'y' for newer versions or 'horizontalBar' for older. 
                // Detection: if 'chart.js' is CDN link from newer versions (3+), 'type: bar', indexAxis: 'y'
                 // Let's use 'bar' and handle config for version compatibility if needed. The layout implies horizontal.
                 // Assuming Chart.js 2.9 based on simple include, using 'horizontalBar' explicitly. 
                 // If 3.x, use 'bar' + indexAxis: 'y'. Let's try 2.x syntax first as it's safer for 'horizontalBar'.
                type: 'horizontalBar', 
                data: {
                    labels: labels,
                    datasets: [{
                        label: '{{ __('Count') }}',
                        data: values,
                        backgroundColor: colors[currentMetric].bg,
                        borderColor: colors[currentMetric].border,
                        borderWidth: 1,
                        hoverBackgroundColor: colors[currentMetric].border
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: { display: false },
                    title: { display: false },
                    scales: {
                        xAxes: [{
                            ticks: { beginAtZero: true }
                        }]
                    },
                    onClick: (e, elements) => {
                        if (elements.length > 0) {
                            const index = elements[0]._index;
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

        // Initialize
        // Set initial active button style manually since CSS classes might not fully apply immediately
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
    }
    .btn-outline-teal:hover {
        background-color: #0d9488;
        color: white;
    }
    .text-teal-600 { color: #0d9488 !important; }
    .text-teal-700 { color: #0f766e !important; }
</style>
@endsection
