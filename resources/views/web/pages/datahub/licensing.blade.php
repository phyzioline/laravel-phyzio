@extends('web.layouts.app')

@section('title', __('Professional Equivalence & Licensing Guide'))

@section('content')
<div class="licensing-guide-wrapper bg-light py-5">
    <div class="container">
        <!-- Header -->
        <div class="text-center mb-5">
            <h2 class="text-teal-700 font-weight-bold" style="color: #0d9488;">{{ __('Professional Equivalence & Licensing Guide') }}</h2>
            <p class="text-muted">{{ __('A step-by-step guide for physical therapists validating qualifications and obtaining licenses globally.') }}</p>
        </div>

        <!-- Selection Wizard -->
        <div class="card shadow border-0 mb-5">
            <div class="card-body p-4">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="font-weight-bold text-teal-600" style="color: #0d9488;">{{ __('1. Select Source Country') }} <small class="text-muted">({{ __('Where you graduated') }})</small></label>
                            <select id="sourceCountry" class="form-control form-control-lg border-teal" style="border-color: #0d9488;">
                                <option value="" selected disabled>{{ __('Choose Country...') }}</option>
                                <option value="egypt">{{ __('Egypt') }}</option>
                                <option value="jordan">{{ __('Jordan') }}</option>
                                <option value="india">{{ __('India') }}</option>
                                <option value="philippines">{{ __('Philippines') }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2 text-center d-flex align-items-center justify-content-center">
                        <div class="arrow-icon bg-teal text-white rounded-circle p-2 shadow-sm d-none d-md-block" style="background-color: #0d9488;">
                            <i class="las la-arrow-right font-weight-bold" style="font-size: 1.5rem;"></i>
                        </div>
                        <div class="arrow-icon bg-teal text-white rounded-circle p-2 shadow-sm d-md-none my-3" style="background-color: #0d9488;">
                            <i class="las la-arrow-down font-weight-bold" style="font-size: 1.5rem;"></i>
                        </div>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                            <label class="font-weight-bold text-teal-600" style="color: #0d9488;">{{ __('2. Select Target Country') }} <small class="text-muted">({{ __('Where you want to practice') }})</small></label>
                            <select id="targetCountry" class="form-control form-control-lg border-teal" style="border-color: #0d9488;">
                                <option value="" selected disabled>{{ __('Choose Country...') }}</option>
                                <option value="oman">{{ __('Oman') }}</option>
                                <option value="saudi_arabia">{{ __('Saudi Arabia') }}</option>
                                <option value="uae">{{ __('UAE') }}</option>
                                <option value="usa">{{ __('USA') }}</option>
                                <option value="uk">{{ __('UK') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dynamic Content Area -->
        <div id="requirementsSection" style="display: none;">
            <div class="row">
                <!-- Tabs Navigation -->
                <div class="col-md-3 mb-4">
                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                        <a class="nav-link active font-weight-bold py-3" id="v-pills-official-tab" data-toggle="pill" href="#v-pills-official" role="tab" aria-controls="v-pills-official" aria-selected="true" style="color: #0d9488;">
                            <i class="las la-file-alt mr-2"></i> {{ __('Official Requirements') }}
                        </a>
                        <a class="nav-link font-weight-bold py-3" id="v-pills-hours-tab" data-toggle="pill" href="#v-pills-hours" role="tab" aria-controls="v-pills-hours" aria-selected="false" style="color: #0d9488;">
                            <i class="las la-clock mr-2"></i> {{ __('Hours & Curriculum') }}
                        </a>
                        <a class="nav-link font-weight-bold py-3" id="v-pills-experience-tab" data-toggle="pill" href="#v-pills-experience" role="tab" aria-controls="v-pills-experience" aria-selected="false" style="color: #0d9488;">
                            <i class="las la-briefcase mr-2"></i> {{ __('Required Experience') }}
                        </a>
                        <a class="nav-link font-weight-bold py-3" id="v-pills-exams-tab" data-toggle="pill" href="#v-pills-exams" role="tab" aria-controls="v-pills-exams" aria-selected="false" style="color: #0d9488;">
                            <i class="las la-edit mr-2"></i> {{ __('Mandatory Exams') }}
                        </a>
                    </div>
                </div>

                <!-- Tabs Content -->
                <div class="col-md-9">
                    <div class="tab-content" id="v-pills-tabContent">
                        
                        <!-- Official Requirements Tab -->
                        <div class="tab-pane fade show active" id="v-pills-official" role="tabpanel" aria-labelledby="v-pills-official-tab">
                            <div class="card shadow-sm border-0">
                                <div class="card-body p-4">
                                    <h4 class="text-teal-700 font-weight-bold mb-4" style="color: #0d9488;">{{ __('Official Documentation') }}</h4>
                                    <ul class="list-group list-group-flush">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="las la-check-circle text-success mr-2"></i> {{ __('Bachelor Degree (Authenticated)') }}</span>
                                            <button class="btn btn-sm btn-outline-secondary">{{ __('View Template') }}</button>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="las la-check-circle text-success mr-2"></i> {{ __('Good Standing Certificate') }}</span>
                                            <span class="badge badge-warning">{{ __('Expires in 6 months') }}</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="las la-check-circle text-success mr-2"></i> {{ __('DataFlow Verification Report') }}</span>
                                            <a href="#" class="text-teal-600 font-weight-bold" style="color: #0d9488;">{{ __('Go to DataFlow') }}</a>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <span><i class="las la-check-circle text-success mr-2"></i> {{ __('Police Clearance Certificate') }}</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <!-- Hours Tab -->
                        <div class="tab-pane fade" id="v-pills-hours" role="tabpanel" aria-labelledby="v-pills-hours-tab">
                            <div class="card shadow-sm border-0">
                                <div class="card-body p-4">
                                    <h4 class="text-teal-700 font-weight-bold mb-4" style="color: #0d9488;">{{ __('Curriculum & Credit Hours') }}</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="alert alert-light border-teal" style="border-left: 4px solid #0d9488;">
                                                <h6 class="font-weight-bold">{{ __('Minimum Total Hours') }}</h6>
                                                <p class="h3 text-teal-700 mb-0" style="color: #0d9488;">4,500 {{ __('Hours') }}</p>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="alert alert-light border-teal" style="border-left: 4px solid #0d9488;">
                                                <h6 class="font-weight-bold">{{ __('Clinical Training') }}</h6>
                                                <p class="h3 text-teal-700 mb-0" style="color: #0d9488;">1,000 {{ __('Hours') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <h5 class="font-weight-bold mt-4">{{ __('Hours Calculator') }}</h5>
                                    <p class="text-muted">{{ __('Enter your transcript hours to check eligibility.') }}</p>
                                    <div class="form-row align-items-end">
                                        <div class="col">
                                            <input type="number" class="form-control" placeholder="{{ __('Your Total Hours') }}">
                                        </div>
                                        <div class="col">
                                            <input type="number" class="form-control" placeholder="{{ __('Your Clinical Hours') }}">
                                        </div>
                                        <div class="col-auto">
                                            <button class="btn btn-teal text-white" style="background-color: #0d9488;">{{ __('Calculate') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Experience Tab -->
                        <div class="tab-pane fade" id="v-pills-experience" role="tabpanel" aria-labelledby="v-pills-experience-tab">
                            <div class="card shadow-sm border-0">
                                <div class="card-body p-4">
                                    <h4 class="text-teal-700 font-weight-bold mb-4" style="color: #0d9488;">{{ __('Experience & Internship') }}</h4>
                                    <div class="media mb-4">
                                        <div class="media-body">
                                            <h5 class="mt-0 font-weight-bold">{{ __('Mandatory Internship') }}</h5>
                                            <p>{{ __('Must complete a 12-month rotating internship including Orthopedics, Neurology, Pediatrics, and ICU.') }}</p>
                                        </div>
                                    </div>
                                    <div class="media mb-4">
                                        <div class="media-body">
                                            <h5 class="mt-0 font-weight-bold">{{ __('Post-Licensure Experience') }}</h5>
                                            <p>{{ __('Minimum 2 years of full-time work experience in a recognized hospital or clinic after obtaining the home country license.') }}</p>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar bg-teal" role="progressbar" style="width: 100%; background-color: #0d9488;" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">{{ __('2 Years Required') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Exams Tab -->
                        <div class="tab-pane fade" id="v-pills-exams" role="tabpanel" aria-labelledby="v-pills-exams-tab">
                            <div class="card shadow-sm border-0">
                                <div class="card-body p-4">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h4 class="text-teal-700 font-weight-bold mb-4" style="color: #0d9488;">{{ __('Licensing Examination') }}</h4>
                                            <table class="table">
                                                <tbody>
                                                    <tr>
                                                        <th scope="row" class="bg-light">{{ __('Exam Name') }}</th>
                                                        <td>Prometric / HAAD / OMSB</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row" class="bg-light">{{ __('Format') }}</th>
                                                        <td>{{ __('Computer Based Test (CBT)') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row" class="bg-light">{{ __('Passing Score') }}</th>
                                                        <td>60%</td>
                                                    </tr>
                                                    <tr>
                                                        <th scope="row" class="bg-light">{{ __('Attempts Allowed') }}</th>
                                                        <td>3 {{ __('Attempts per year') }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <a href="#" class="btn btn-teal btn-block text-white" style="background-color: #0d9488;">{{ __('Register for Exam') }}</a>
                                        </div>
                                        <div class="col-md-4 border-left">
                                            <h6 class="text-center font-weight-bold mb-3">{{ __('Success Rate') }}</h6>
                                            <div class="text-center">
                                                 <div style="height: 200px; width: 100%;">
                                                    <!-- Simple CSS Bar Chart for visual punch -->
                                                    <div class="d-flex justify-content-center align-items-end h-100 pb-2">
                                                        <div class="mx-2 text-center">
                                                            <div class="bg-secondary rounded-top" style="width: 40px; height: 60%; opacity: 0.5;"></div>
                                                            <small class="d-block mt-1">{{ __('Global') }}</small>
                                                        </div>
                                                        <div class="mx-2 text-center">
                                                            <div class="bg-teal rounded-top" style="width: 40px; height: 85%; background-color: #0d9488;"></div>
                                                            <small class="d-block mt-1 font-weight-bold text-teal-700" style="color: #0d9488;">{{ __('Target') }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                                <p class="small text-muted mt-2">{{ __('Pass rate for this exam pathway') }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div id="emptyState" class="text-center py-5">
            <i class="las la-globe-americas text-muted mb-3" style="font-size: 4rem; opacity: 0.3;"></i>
            <h4 class="text-muted">{{ __('Please select both Source and Target countries to view requirements.') }}</h4>
        </div>

    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sourceSelect = document.getElementById('sourceCountry');
        const targetSelect = document.getElementById('targetCountry');
        const requirementsSection = document.getElementById('requirementsSection');
        const emptyState = document.getElementById('emptyState');

        function checkSelections() {
            if (sourceSelect.value && targetSelect.value) {
                requirementsSection.style.display = 'block';
                emptyState.style.display = 'none';

                // Scroll to results smoothly
                requirementsSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
            } else {
                requirementsSection.style.display = 'none';
                emptyState.style.display = 'block';
            }
        }

        sourceSelect.addEventListener('change', checkSelections);
        targetSelect.addEventListener('change', checkSelections);
    });
</script>
<style>
    .nav-pills .nav-link {
        color: #495057;
        border-radius: 0.5rem;
        transition: all 0.3s;
    }
    .nav-pills .nav-link.active {
        background-color: #e0f2f1;
        color: #00695c !important; 
        border-right: 4px solid #00695c;
    }
    .btn-teal {
        background-color: #0d9488;
        border-color: #0d9488;
    }
    .btn-teal:hover {
         background-color: #0f766e;
         border-color: #0f766e;
    }
    .border-teal {
        border-color: #0d9488 !important;
    }
</style>
@endpush
@endsection
