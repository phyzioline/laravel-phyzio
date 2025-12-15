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

        <!-- 3D Globe Visualization -->
        <div class="card shadow-sm border-0 mb-5">
            <div class="card-body p-4">
                <h4 class="card-title text-center text-teal-700 font-weight-bold mb-4" style="color: #0d9488;">{{ __('Interactive Global Data Map') }}</h4>
                <div id="globeViz" style="width: 100%; height: 600px; border-radius: 12px; overflow: hidden; background: #000;"></div>
                <div class="text-center mt-3 text-muted">
                    <small>{{ __('Click on any marked country point to view detailed statistics.') }}</small>
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
<script src="//unpkg.com/globe.gl"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Enhanced Mock Data (Global Coverage with Coordinates & Salary) ---
        const rawData = [
            // North America
            { country: 'USA', continent: 'north_america', therapists: 247000, population: 331000000, schools: 275, centers: 18000, lat: 37.0902, lng: -95.7129, salary: 89000 },
            { country: 'Canada', continent: 'north_america', therapists: 29000, population: 38000000, schools: 15, centers: 1600, lat: 56.1304, lng: -106.3468, salary: 74000 },
            { country: 'Mexico', continent: 'north_america', therapists: 18000, population: 126000000, schools: 45, centers: 900, lat: 23.6345, lng: -102.5528, salary: 18000 },
            
            // Europe
            { country: 'Germany', continent: 'europe', therapists: 195000, population: 83000000, schools: 100, centers: 9000, lat: 51.1657, lng: 10.4515, salary: 55000 },
            { country: 'UK', continent: 'europe', therapists: 62000, population: 67000000, schools: 50, centers: 3500, lat: 55.3781, lng: -3.4360, salary: 52000 },
            { country: 'France', continent: 'europe', therapists: 95000, population: 65000000, schools: 55, centers: 6500, lat: 46.2276, lng: 2.2137, salary: 48000 },
            { country: 'Italy', continent: 'europe', therapists: 68000, population: 60000000, schools: 38, centers: 4200, lat: 41.8719, lng: 12.5674, salary: 42000 },
            { country: 'Spain', continent: 'europe', therapists: 58000, population: 47000000, schools: 45, centers: 3800, lat: 40.4637, lng: -3.7492, salary: 38000 },
            { country: 'Netherlands', continent: 'europe', therapists: 24000, population: 17000000, schools: 14, centers: 2000, lat: 52.1326, lng: 5.2913, salary: 60000 },
            { country: 'Sweden', continent: 'europe', therapists: 15000, population: 10000000, schools: 9, centers: 1000, lat: 60.1282, lng: 18.6435, salary: 58000 },
            { country: 'Belgium', continent: 'europe', therapists: 32000, population: 11500000, schools: 12, centers: 1500, lat: 50.5039, lng: 4.4699, salary: 50000 },
            { country: 'Poland', continent: 'europe', therapists: 45000, population: 38000000, schools: 25, centers: 1800, lat: 51.9194, lng: 19.1451, salary: 28000 },
            { country: 'Switzerland', continent: 'europe', therapists: 12000, population: 8600000, schools: 5, centers: 800, lat: 46.8182, lng: 8.2275, salary: 75000 },
            { country: 'Norway', continent: 'europe', therapists: 11000, population: 5400000, schools: 4, centers: 600, lat: 60.4720, lng: 8.4689, salary: 62000 },
            { country: 'Denmark', continent: 'europe', therapists: 10000, population: 5800000, schools: 5, centers: 550, lat: 56.2639, lng: 9.5018, salary: 60000 },
            { country: 'Finland', continent: 'europe', therapists: 9000, population: 5500000, schools: 5, centers: 500, lat: 61.9241, lng: 25.7482, salary: 54000 },
            { country: 'Ireland', continent: 'europe', therapists: 5000, population: 5000000, schools: 4, centers: 300, lat: 53.1424, lng: -7.6921, salary: 55000 },
            { country: 'Portugal', continent: 'europe', therapists: 12000, population: 10000000, schools: 10, centers: 500, lat: 39.3999, lng: -8.2245, salary: 28000 },
            { country: 'Greece', continent: 'europe', therapists: 8000, population: 10400000, schools: 6, centers: 400, lat: 39.0742, lng: 21.8243, salary: 25000 },
            
            // Asia
            { country: 'Japan', continent: 'asia', therapists: 130000, population: 126000000, schools: 120, centers: 4500, lat: 36.2048, lng: 138.2529, salary: 45000 },
            { country: 'China', continent: 'asia', therapists: 90000, population: 1400000000, schools: 90, centers: 4000, lat: 35.8617, lng: 104.1954, salary: 25000 },
            { country: 'India', continent: 'asia', therapists: 110000, population: 1380000000, schools: 350, centers: 6000, lat: 20.5937, lng: 78.9629, salary: 8000 },
            { country: 'South Korea', continent: 'asia', therapists: 35000, population: 51000000, schools: 50, centers: 1400, lat: 35.9078, lng: 127.7669, salary: 42000 },
            { country: 'Saudi Arabia', continent: 'asia', therapists: 14000, population: 35000000, schools: 18, centers: 950, lat: 23.8859, lng: 45.0792, salary: 48000 },
            { country: 'UAE', continent: 'asia', therapists: 6500, population: 9900000, schools: 6, centers: 450, lat: 23.4241, lng: 53.8478, salary: 65000 },
            { country: 'Jordan', continent: 'asia', therapists: 7500, population: 10200000, schools: 10, centers: 300, lat: 30.5852, lng: 36.2384, salary: 18000 },
            { country: 'Philippines', continent: 'asia', therapists: 22000, population: 110000000, schools: 40, centers: 700, lat: 12.8797, lng: 121.7740, salary: 9000 },
            { country: 'Thailand', continent: 'asia', therapists: 10000, population: 70000000, schools: 14, centers: 500, lat: 15.8700, lng: 100.9925, salary: 12000 },
            { country: 'Vietnam', continent: 'asia', therapists: 8000, population: 97000000, schools: 10, centers: 400, lat: 14.0583, lng: 108.2772, salary: 10000 },
            { country: 'Indonesia', continent: 'asia', therapists: 15000, population: 273000000, schools: 20, centers: 600, lat: -0.7893, lng: 113.9213, salary: 8000 },
            { country: 'Malaysia', continent: 'asia', therapists: 5000, population: 32000000, schools: 8, centers: 250, lat: 4.2105, lng: 101.9758, salary: 14000 },
            { country: 'Singapore', continent: 'asia', therapists: 2500, population: 5700000, schools: 2, centers: 150, lat: 1.3521, lng: 103.8198, salary: 50000 },
            { country: 'Turkey', continent: 'asia', therapists: 25000, population: 84000000, schools: 30, centers: 1200, lat: 38.9637, lng: 35.2433, salary: 15000 },
            { country: 'Israel', continent: 'asia', therapists: 7000, population: 9000000, schools: 5, centers: 300, lat: 31.0461, lng: 34.8516, salary: 55000 },
            { country: 'Taiwan', continent: 'asia', therapists: 8000, population: 23000000, schools: 10, centers: 350, lat: 23.6978, lng: 120.9605, salary: 38000 },

            // South America
            { country: 'Brazil', continent: 'south_america', therapists: 280000, population: 212000000, schools: 250, centers: 7000, lat: -14.2350, lng: -51.9253, salary: 18000 },
            { country: 'Argentina', continent: 'south_america', therapists: 40000, population: 45000000, schools: 30, centers: 1400, lat: -38.4161, lng: -63.6167, salary: 14000 },
            { country: 'Colombia', continent: 'south_america', therapists: 15000, population: 50000000, schools: 22, centers: 850, lat: 4.5709, lng: -74.2973, salary: 12000 },
            { country: 'Chile', continent: 'south_america', therapists: 18000, population: 19000000, schools: 20, centers: 600, lat: -35.6751, lng: -71.5430, salary: 22000 },
            { country: 'Peru', continent: 'south_america', therapists: 9000, population: 33000000, schools: 12, centers: 400, lat: -9.1900, lng: -75.0152, salary: 13000 },
            { country: 'Venezuela', continent: 'south_america', therapists: 6000, population: 28000000, schools: 8, centers: 300, lat: 6.4238, lng: -66.5897, salary: 5000 },

            // Africa
            { country: 'Egypt', continent: 'africa', therapists: 55000, population: 102000000, schools: 30, centers: 1500, lat: 26.8206, lng: 30.8025, salary: 6000 },
            { country: 'South Africa', continent: 'africa', therapists: 9500, population: 59000000, schools: 15, centers: 600, lat: -30.5595, lng: 22.9375, salary: 32000 },
            { country: 'Nigeria', continent: 'africa', therapists: 6000, population: 206000000, schools: 10, centers: 250, lat: 9.0820, lng: 8.6753, salary: 8000 },
            { country: 'Morocco', continent: 'africa', therapists: 9000, population: 37000000, schools: 7, centers: 500, lat: 31.7917, lng: -7.0926, salary: 11000 },
            { country: 'Kenya', continent: 'africa', therapists: 3000, population: 53000000, schools: 5, centers: 150, lat: -0.0236, lng: 37.9062, salary: 9000 },
            { country: 'Ghana', continent: 'africa', therapists: 1500, population: 31000000, schools: 3, centers: 100, lat: 7.9465, lng: -1.0232, salary: 7000 },
            { country: 'Algeria', continent: 'africa', therapists: 7000, population: 43000000, schools: 6, centers: 300, lat: 28.0339, lng: 1.6596, salary: 10000 },

            // Oceania
            { country: 'Australia', continent: 'oceania', therapists: 38000, population: 25000000, schools: 25, centers: 2000, lat: -25.2744, lng: 133.7751, salary: 68000 },
            { country: 'New Zealand', continent: 'oceania', therapists: 7000, population: 5000000, schools: 5, centers: 450, lat: -40.9006, lng: 174.8860, salary: 55000 }
        ];

        // Initialize Globe
        const globeContainer = document.getElementById('globeViz');
        const world = Globe()
            (globeContainer)
            .globeImageUrl('//unpkg.com/three-globe/example/img/earth-blue-marble.jpg')
            .bumpImageUrl('//unpkg.com/three-globe/example/img/earth-topology.png')
            .backgroundImageUrl('//unpkg.com/three-globe/example/img/night-sky.png')
            .pointsData(rawData)
            .pointAltitude(0.15)
            .pointColor(() => '#0d9488')
            .pointRadius(0.8)
            .pointLabel(d => `<div style="color: #fff; background: rgba(0,0,0,0.8); padding: 5px; border-radius: 4px;"><b>${d.country}</b><br>Therapists: ${d.therapists.toLocaleString()}</div>`)
            .onPointClick(d => {
                updateModal(d);
                $('#countryDetailModal').modal('show'); // Ensure ID matches your HTML (countryDetailModal or countryModal)
                
                // Focus on country
                world.pointOfView({ lat: d.lat, lng: d.lng, altitude: 1.5 }, 1500);
            });
            
        // Auto-rotate
        world.controls().autoRotate = true;
        world.controls().autoRotateSpeed = 0.5;

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
