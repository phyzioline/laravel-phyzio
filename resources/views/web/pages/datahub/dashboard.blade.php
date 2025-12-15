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

        <!-- Flat World Map -->
        <div class="card shadow-sm border-0 mb-5">
            <div class="card-body p-4">
                <h4 class="card-title text-center text-teal-700 font-weight-bold mb-4" style="color: #0d9488;">{{ __('Interactive Global Data Map') }}</h4>
                <div id="world-map-markers" style="width: 100%; height: 500px; border-radius: 12px; overflow: hidden; background: #fff;"></div>
                <div class="text-center mt-3 text-muted">
                    <small>{{ __('Hover over or click on highlighted countries to view statistics.') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Country Detail Modal -->
<div class="modal fade" id="countryDetailModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header text-white" style="background-color: #00897b;">
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
                            <h4 class="font-weight-bold text-dark" id="modalTherapists" style="color: #333;">0</h4>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="detail-item p-3 rounded bg-light">
                            <i class="las la-users text-teal-600 mb-2" style="font-size: 2rem; color: #0d9488;"></i>
                            <h6 class="text-muted mb-1">{{ __('Population') }}</h6>
                            <h4 class="font-weight-bold text-dark" id="modalPopulation" style="color: #333;">0M</h4>
                        </div>
                    </div>
                </div>
                 <div class="row text-center">
                    <div class="col-6 mb-3">
                         <div class="detail-item p-3 rounded bg-light">
                            <i class="las la-university text-teal-600 mb-2" style="font-size: 2rem; color: #0d9488;"></i>
                            <h6 class="text-muted mb-1">{{ __('PT Schools') }}</h6>
                            <h4 class="font-weight-bold text-dark" id="modalSchools" style="color: #333;">0</h4>
                        </div>
                    </div>
                     <div class="col-6 mb-3">
                         <div class="detail-item p-3 rounded bg-light">
                            <i class="las la-hospital-alt text-teal-600 mb-2" style="font-size: 2rem; color: #0d9488;"></i>
                            <h6 class="text-muted mb-1">{{ __('Rehab Centers') }}</h6>
                            <h4 class="font-weight-bold text-dark" id="modalCenters" style="color: #333;">0</h4>
                        </div>
                    </div>
                </div>
                <!-- Average Salary Section -->
                <div class="row text-center">
                    <div class="col-12 mb-3">
                        <div class="detail-item p-3 rounded bg-light">
                            <i class="las la-money-bill-wave text-teal-600 mb-2" style="font-size: 2rem; color: #0d9488;"></i>
                            <h6 class="text-muted mb-1">{{ __('Avg. Amount') }}</h6>
                            <h4 class="font-weight-bold text-dark" id="modalSalary" style="color: #333;">$0k</h4>
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
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap/dist/css/jsvectormap.min.css" />
<script src="https://cdn.jsdelivr.net/npm/jsvectormap"></script>
<script src="https://cdn.jsdelivr.net/npm/jsvectormap/dist/maps/world.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Enhanced Data (Global Coverage with ISO Codes & Salary) ---
        const rawData = [
            // North America
            { country: 'USA', code: 'US', continent: 'north_america', therapists: 247000, population: 331000000, schools: 275, centers: 18000, salary: 89000 },
            { country: 'Canada', code: 'CA', continent: 'north_america', therapists: 29000, population: 38000000, schools: 15, centers: 1600, salary: 74000 },
            { country: 'Mexico', code: 'MX', continent: 'north_america', therapists: 18000, population: 126000000, schools: 45, centers: 900, salary: 18000 },
            
            // Europe
            { country: 'Germany', code: 'DE', continent: 'europe', therapists: 195000, population: 83000000, schools: 100, centers: 9000, salary: 55000 },
            { country: 'UK', code: 'GB', continent: 'europe', therapists: 62000, population: 67000000, schools: 50, centers: 3500, salary: 52000 },
            { country: 'France', code: 'FR', continent: 'europe', therapists: 95000, population: 65000000, schools: 55, centers: 6500, salary: 48000 },
            { country: 'Italy', code: 'IT', continent: 'europe', therapists: 68000, population: 60000000, schools: 38, centers: 4200, salary: 42000 },
            { country: 'Spain', code: 'ES', continent: 'europe', therapists: 58000, population: 47000000, schools: 45, centers: 3800, salary: 38000 },
            { country: 'Netherlands', code: 'NL', continent: 'europe', therapists: 24000, population: 17000000, schools: 14, centers: 2000, salary: 60000 },
            { country: 'Sweden', code: 'SE', continent: 'europe', therapists: 15000, population: 10000000, schools: 9, centers: 1000, salary: 58000 },
            { country: 'Belgium', code: 'BE', continent: 'europe', therapists: 32000, population: 11500000, schools: 12, centers: 1500, salary: 50000 },
            { country: 'Poland', code: 'PL', continent: 'europe', therapists: 45000, population: 38000000, schools: 25, centers: 1800, salary: 28000 },
            { country: 'Switzerland', code: 'CH', continent: 'europe', therapists: 12000, population: 8600000, schools: 5, centers: 800, salary: 75000 },
            { country: 'Norway', code: 'NO', continent: 'europe', therapists: 11000, population: 5400000, schools: 4, centers: 600, salary: 62000 },
            { country: 'Denmark', code: 'DK', continent: 'europe', therapists: 10000, population: 5800000, schools: 5, centers: 550, salary: 60000 },
            { country: 'Finland', code: 'FI', continent: 'europe', therapists: 9000, population: 5500000, schools: 5, centers: 500, salary: 54000 },
            { country: 'Ireland', code: 'IE', continent: 'europe', therapists: 5000, population: 5000000, schools: 4, centers: 300, salary: 55000 },
            { country: 'Portugal', code: 'PT', continent: 'europe', therapists: 12000, population: 10000000, schools: 10, centers: 500, salary: 28000 },
            { country: 'Greece', code: 'GR', continent: 'europe', therapists: 8000, population: 10400000, schools: 6, centers: 400, salary: 25000 },
            
            // Asia
            { country: 'Japan', code: 'JP', continent: 'asia', therapists: 130000, population: 126000000, schools: 120, centers: 4500, salary: 45000 },
            { country: 'China', code: 'CN', continent: 'asia', therapists: 90000, population: 1400000000, schools: 90, centers: 4000, salary: 25000 },
            { country: 'India', code: 'IN', continent: 'asia', therapists: 110000, population: 1380000000, schools: 350, centers: 6000, salary: 8000 },
            { country: 'South Korea', code: 'KR', continent: 'asia', therapists: 35000, population: 51000000, schools: 50, centers: 1400, salary: 42000 },
            { country: 'Saudi Arabia', code: 'SA', continent: 'asia', therapists: 14000, population: 35000000, schools: 18, centers: 950, salary: 48000 },
            { country: 'UAE', code: 'AE', continent: 'asia', therapists: 6500, population: 9900000, schools: 6, centers: 450, salary: 65000 },
            { country: 'Jordan', code: 'JO', continent: 'asia', therapists: 7500, population: 10200000, schools: 10, centers: 300, salary: 18000 },
            { country: 'Philippines', code: 'PH', continent: 'asia', therapists: 22000, population: 110000000, schools: 40, centers: 700, salary: 9000 },
            { country: 'Thailand', code: 'TH', continent: 'asia', therapists: 10000, population: 70000000, schools: 14, centers: 500, salary: 12000 },
            { country: 'Vietnam', code: 'VN', continent: 'asia', therapists: 8000, population: 97000000, schools: 10, centers: 400, salary: 10000 },
            { country: 'Indonesia', code: 'ID', continent: 'asia', therapists: 15000, population: 273000000, schools: 20, centers: 600, salary: 8000 },
            { country: 'Malaysia', code: 'MY', continent: 'asia', therapists: 5000, population: 32000000, schools: 8, centers: 250, salary: 14000 },
            { country: 'Singapore', code: 'SG', continent: 'asia', therapists: 2500, population: 5700000, schools: 2, centers: 150, salary: 50000 },
            { country: 'Turkey', code: 'TR', continent: 'asia', therapists: 25000, population: 84000000, schools: 30, centers: 1200, salary: 15000 },
            { country: 'Israel', code: 'IL', continent: 'asia', therapists: 7000, population: 9000000, schools: 5, centers: 300, salary: 55000 },
            { country: 'Taiwan', code: 'TW', continent: 'asia', therapists: 8000, population: 23000000, schools: 10, centers: 350, salary: 38000 },

            // South America
            { country: 'Brazil', code: 'BR', continent: 'south_america', therapists: 280000, population: 212000000, schools: 250, centers: 7000, salary: 18000 },
            { country: 'Argentina', code: 'AR', continent: 'south_america', therapists: 40000, population: 45000000, schools: 30, centers: 1400, salary: 14000 },
            { country: 'Colombia', code: 'CO', continent: 'south_america', therapists: 15000, population: 50000000, schools: 22, centers: 850, salary: 12000 },
            { country: 'Chile', code: 'CL', continent: 'south_america', therapists: 18000, population: 19000000, schools: 20, centers: 600, salary: 22000 },
            { country: 'Peru', code: 'PE', continent: 'south_america', therapists: 9000, population: 33000000, schools: 12, centers: 400, salary: 13000 },
            { country: 'Venezuela', code: 'VE', continent: 'south_america', therapists: 6000, population: 28000000, schools: 8, centers: 300, salary: 5000 },

            // Africa
            { country: 'Egypt', code: 'EG', continent: 'africa', therapists: 55000, population: 102000000, schools: 30, centers: 1500, salary: 6000 },
            { country: 'South Africa', code: 'ZA', continent: 'africa', therapists: 9500, population: 59000000, schools: 15, centers: 600, salary: 32000 },
            { country: 'Nigeria', code: 'NG', continent: 'africa', therapists: 6000, population: 206000000, schools: 10, centers: 250, salary: 8000 },
            { country: 'Morocco', code: 'MA', continent: 'africa', therapists: 9000, population: 37000000, schools: 7, centers: 500, salary: 11000 },
            { country: 'Kenya', code: 'KE', continent: 'africa', therapists: 3000, population: 53000000, schools: 5, centers: 150, salary: 9000 },
            { country: 'Ghana', code: 'GH', continent: 'africa', therapists: 1500, population: 31000000, schools: 3, centers: 100, salary: 7000 },
            { country: 'Algeria', code: 'DZ', continent: 'africa', therapists: 7000, population: 43000000, schools: 6, centers: 300, salary: 10000 },

            // Oceania
            { country: 'Australia', code: 'AU', continent: 'oceania', therapists: 38000, population: 25000000, schools: 25, centers: 2000, salary: 68000 },
            { country: 'New Zealand', code: 'NZ', continent: 'oceania', therapists: 7000, population: 5000000, schools: 5, centers: 450, salary: 55000 }
        ];

        // Create Map Data Object
        const mapData = {};
        rawData.forEach(item => {
            mapData[item.code] = item;
        });

        // Initialize Map
        const map = new jsVectorMap({
            selector: '#world-map-markers',
            map: 'world',
            backgroundColor: 'transparent',
            draggable: true,
            zoomButtons: true,
            zoomOnScroll: false,
            regionStyle: {
                initial: {
                    fill: '#e9ecef',
                    stroke: '#ced4da',
                    strokeWidth: 1,
                    fillOpacity: 1
                },
                hover: {
                    fillOpacity: 0.8,
                    cursor: 'pointer'
                },
                selected: {
                    fill: '#0d9488'
                }
            },
            // Highlight regions with data
            series: {
                regions: [{
                    attribute: 'fill',
                    legend: {
                        title: 'Therapists Presence',
                        vertical: true
                    },
                    scale: {
                        'US': '#0d9488',
                        'CA': '#0d9488',
                        // We can just set a static color for highlighting or use a scale based on therapist count
                         // Simpler approach: highlight all countries in our list with Teal
                    },
                     values: (function(){
                        let vals = {};
                        rawData.forEach(d => { vals[d.code] = '#0d9488'; });
                        return vals;
                    })()
                }]
            },
            onRegionClick: function(event, code) {
                const data = mapData[code];
                if(data) {
                    updateModal(data);
                    $('#countryDetailModal').modal('show');
                }
            },
            onRegionTooltipShow(event, tooltip, code) {
                const data = mapData[code];
                if(data) {
                    tooltip.text(
                        `<div class="text-center">
                            <strong class="d-block mb-1">${data.country}</strong>
                            <small>Therapists: ${data.therapists.toLocaleString()}</small>
                        </div>`,
                        true // Allow HTML
                    );
                }
            }
        });

        // Modal Update Function
        function updateModal(data) {
            document.getElementById('modalCountryName').innerText = data.country;
            document.getElementById('modalTherapists').innerText = data.therapists.toLocaleString();
            
            // Population
            let popDisplay = data.population;
            if (data.population >= 1000000) {
                popDisplay = (data.population / 1000000).toFixed(1) + 'M';
            } else {
                popDisplay = (data.population / 1000).toFixed(1) + 'k';
            }
            document.getElementById('modalPopulation').innerText = popDisplay;
            
            document.getElementById('modalSchools').innerText = data.schools;
            document.getElementById('modalCenters').innerText = data.centers.toLocaleString();
            
            // Salary
            document.getElementById('modalSalary').innerText = '$' + (data.salary ? data.salary.toLocaleString() : 'N/A') + ' / year';

            // Ratio
            const ratio = (data.therapists / data.population) * 100000;
            document.getElementById('modalRatio').innerText = ratio.toFixed(1);
        }
    });
</script>
<style>
    .jvm-tooltip {
        background-color: #333;
        font-family: inherit;
        border-radius: 6px;
        padding: 5px 10px;
    }
    .jvm-zoom-btn {
        background-color: #0d9488;
        padding: 5px;
        border-radius: 4px;
        color: white;
    }
</style>
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
