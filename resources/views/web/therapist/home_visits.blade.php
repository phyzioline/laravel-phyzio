@extends('therapist.layouts.app')

@section('content')
<div class="container-fluid py-4" style="background-color: #f8f9fa;">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-0">{{ __('Home Visits') }}</h2>
            <p class="text-muted">{{ __('View and manage your consultation schedule') }}</p>
        </div>
        <div>
             <button type="button" class="btn btn-outline-secondary mr-2 shadow-sm" id="historyBtn"><i class="las la-history"></i> {{ __('History') }}</button>
             <a href="{{ route('therapist.schedule.index') }}" class="btn btn-primary shadow-sm"><i class="las la-plus"></i> {{ __('New Visit') }}</a>
        </div>
    </div>

    <!-- Active Home Visit Card (Merged from Visits) -->
    @if(isset($activeVisit) && $activeVisit)
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card border-primary shadow-lg">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">{{ __('Active Home Visit') }}: {{ $activeVisit->patient->name ?? __('Patient') }}</h4>
                    <span class="badge badge-light text-primary p-2">{{ strtoupper($activeVisit->status) }}</span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><i class="las la-map-marker"></i> {{ $activeVisit->address }}</p>
                            <a href="https://maps.google.com/?q={{ $activeVisit->location_lat }},{{ $activeVisit->location_lng }}" target="_blank" class="btn btn-outline-primary">
                                <i class="las la-directions"></i> {{ __('Open Navigation') }}
                            </a>
                        </div>
                        <div class="col-md-6 text-center border-left">
                            @if($activeVisit->status == 'accepted')
                                <form action="{{ route('therapist.home_visits.status', $activeVisit->id) }}" method="POST">
                                    @csrf <input type="hidden" name="status" value="on_way">
                                    <button class="btn btn-warning btn-lg btn-block">{{ __('Start Trip') }} <i class="las la-car"></i></button>
                                </form>
                            @elseif($activeVisit->status == 'on_way')
                                <form action="{{ route('therapist.home_visits.status', $activeVisit->id) }}" method="POST">
                                    @csrf <input type="hidden" name="status" value="in_session">
                                    <button class="btn btn-success btn-lg btn-block">{{ __('Arrived') }} <i class="las la-check-circle"></i></button>
                                </form>
                            @elseif($activeVisit->status == 'in_session')
                                <button class="btn btn-info btn-lg btn-block" data-bs-toggle="modal" data-bs-target="#completeVisitModal">{{ __('Complete Session') }} <i class="las la-file-medical"></i></button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Stats Row -->
    <div class="row mb-4">
        <!-- New Visit Stats -->
        <div class="col-md-3">
             <div class="card shadow-sm border-0 border-left-info py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">{{ __('Visit Requests') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $availableVisits->count() ?? 0 }}</div>
                        </div>
                        <div class="col-auto"><i class="las la-car-side fa-2x text-gray-300"></i></div>
                    </div>
                </div>
             </div>
        </div>
        <div class="col-md-3">
             <div class="card shadow-sm border-0 border-left-success py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">{{ __('Completed') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $completed->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="las la-check-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
             </div>
        </div>
        <div class="col-md-3">
             <div class="card shadow-sm border-0 border-left-warning py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">{{ __('Upcoming') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $upcoming->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="las la-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
             </div>
        </div>
         <div class="col-md-3">
             <div class="card shadow-sm border-0 border-left-danger py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">{{ __('Cancelled') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $cancelled->count() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="las la-times-circle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
             </div>
        </div>
    </div>

    <!-- Tabs & Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 bg-white border-bottom-0">
            <ul class="nav nav-pills" id="appointmentTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="upcoming-tab" data-bs-toggle="tab" href="#upcoming" role="tab" aria-controls="upcoming" aria-selected="true">{{ __('Upcoming') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="past-tab" data-bs-toggle="tab" href="#past" role="tab" aria-controls="past" aria-selected="false">{{ __('Past') }}</a>
                </li>
                 <li class="nav-item">
                    <a class="nav-link" id="cancelled-tab" data-bs-toggle="tab" href="#cancelled" role="tab" aria-controls="cancelled" aria-selected="false">{{ __('Cancelled') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="visits-tab" data-bs-toggle="tab" href="#visits" role="tab" aria-controls="visits" aria-selected="false">{{ __('Home Visit Requests') }}</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="appointmentTabsContent">
                <!-- Upcoming Tab -->
                <div class="tab-pane fade show active" id="upcoming" role="tabpanel" aria-labelledby="upcoming-tab">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th>{{ __('Patient Name') }}</th>
                                    <th>{{ __('Type') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Time') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th class="text-center">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($upcoming as $appointment)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-primary text-white rounded-circle mr-2 d-flex align-items-center justify-content-center font-weight-bold" style="width: 35px; height: 35px;">
                                                {{ substr($appointment->patient->name ?? 'U', 0, 2) }}
                                            </div>
                                            <span class="font-weight-bold">{{ $appointment->patient->name ?? 'Unknown Patient' }}</span>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-light border">{{ $appointment->complain_type ?? $appointment->type ?? __('Home Visit') }}</span></td>
                                    <td>{{ $appointment->scheduled_at ? \Carbon\Carbon::parse($appointment->scheduled_at)->format('M d, Y') : ($appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') : 'N/A') }}</td>
                                    <td>{{ $appointment->scheduled_at ? \Carbon\Carbon::parse($appointment->scheduled_at)->format('h:i A') : ($appointment->appointment_time ? \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') : 'N/A') }}</td>
                                    <td><span class="badge badge-warning">{{ ucfirst($appointment->status) }}</span></td>
                                    <td class="text-center">
                                        <a href="{{ route('therapist.home_visits.show', $appointment->id) }}" class="btn btn-sm btn-light border shadow-sm" title="{{ __('View Details') }}"><i class="las la-eye"></i></a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                         <div class="text-gray-500">{{ __('No upcoming home visits found.') }}</div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Past Tab -->
                <div class="tab-pane fade" id="past" role="tabpanel" aria-labelledby="past-tab">
                     <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th>{{ __('Patient Name') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th class="text-center">{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($past as $appointment)
                                <tr>
                                    <td>{{ $appointment->patient->name ?? ($appointment->guest_name ?? 'Unknown') }}</td>
                                    <td>{{ $appointment->scheduled_at ? \Carbon\Carbon::parse($appointment->scheduled_at)->format('M d, Y') : ($appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') : 'N/A') }}</td>
                                    <td><span class="badge badge-light border">{{ ucfirst($appointment->status) }}</span></td>
                                    <td class="text-center">
                                        <a href="{{ route('therapist.home_visits.show', $appointment->id) }}" class="btn btn-sm btn-light border shadow-sm" title="{{ __('View Details') }}"><i class="las la-eye"></i></a>
                                    </td>
                                </tr>
                                @empty
                                     <tr><td colspan="4" class="text-center">{{ __('No past home visits.') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                 <!-- Cancelled Tab -->
                <div class="tab-pane fade" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">
                     <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th>{{ __('Patient Name') }}</th>
                                    <th>{{ __('Date') }}</th>
                                    <th>{{ __('Reason') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cancelled as $appointment)
                                <tr>
                                    <td>{{ $appointment->patient->name ?? ($appointment->guest_name ?? 'Unknown') }}</td>
                                    <td>{{ $appointment->scheduled_at ? \Carbon\Carbon::parse($appointment->scheduled_at)->format('M d, Y') : ($appointment->appointment_date ? \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') : 'N/A') }}</td>
                                    <td>{{ $appointment->cancellation_reason ?? $appointment->notes ?? __('No reason provided') }}</td>
                                </tr>
                                @empty
                                     <tr><td colspan="3" class="text-center">{{ __('No cancelled home visits.') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Home Visit Requests Tab -->
                <div class="tab-pane fade" id="visits" role="tabpanel" aria-labelledby="visits-tab">
                     <div class="table-responsive">
                        <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                            <thead class="bg-light text-dark">
                                <tr>
                                    <th>{{ __('Urgency') }}</th>
                                    <th>{{ __('Condition') }}</th>
                                    <th>{{ __('Location') }}</th>
                                    <th>{{ __('Time') }}</th>
                                    <th>{{ __('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($availableVisits ?? [] as $visit)
                                <tr>
                                    <td>
                                        @if($visit->urgency == 'urgent')
                                            <span class="badge badge-danger">{{ __('URGENT') }}</span>
                                        @else
                                            <span class="badge badge-primary">{{ __('Scheduled') }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $visit->complain_type }}</td>
                                    <td>{{ $visit->city }} <small class="text-muted d-block">{{ Str::limit($visit->address, 30) }}</small></td>
                                    <td>{{ $visit->scheduled_at->diffForHumans() }}</td>
                                    <td>
                                        <form action="{{ route('therapist.home_visits.accept', $visit->id) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-sm btn-primary">{{ __('Accept Visit') }}</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                    <tr><td colspan="5" class="text-center">{{ __('No new visit requests nearby.') }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Complete Visit Modal (Merged) -->
@if(isset($activeVisit) && $activeVisit)
<div class="modal fade" id="completeVisitModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('therapist.home_visits.complete', $activeVisit->id) }}" method="POST">
                @csrf
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title">{{ __('Complete Visit & Clinical Notes') }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ __('Chief Complaint') }}</label>
                        <input type="text" name="chief_complaint" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Assessment Findings') }}</label>
                        <textarea name="assessment_findings[]" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Treatment Performed') }}</label>
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="treatment_performed[]" value="Manual Therapy" class="custom-control-input" id="tx_manual">
                            <label class="custom-control-label" for="tx_manual">{{ __('Manual Therapy') }}</label>
                        </div>
                         <div class="custom-control custom-checkbox">
                            <input type="checkbox" name="treatment_performed[]" value="TherEx" class="custom-control-input" id="tx_ex">
                            <label class="custom-control-label" for="tx_ex">{{ __('Therapeutic Exercise') }}</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-info">{{ __('Submit & Finish') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
    // Function to switch tabs (works with both Bootstrap 4 and 5)
    function switchToTab(tabName) {
        // Remove active class from all tabs and panes
        document.querySelectorAll('#appointmentTabs .nav-link').forEach(function(tab) {
            tab.classList.remove('active');
            tab.setAttribute('aria-selected', 'false');
        });
        document.querySelectorAll('.tab-pane').forEach(function(pane) {
            pane.classList.remove('show', 'active');
        });
        
        // Add active class to selected tab and pane
        const targetTab = document.getElementById(tabName + '-tab');
        const targetPane = document.getElementById(tabName);
        
        if (targetTab && targetPane) {
            targetTab.classList.add('active');
            targetTab.setAttribute('aria-selected', 'true');
            targetPane.classList.add('show', 'active');
            
            // Scroll to tabs section smoothly
            setTimeout(function() {
                const tabsElement = document.getElementById('appointmentTabs');
                if (tabsElement) {
                    tabsElement.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            }, 100);
        }
    }

    // Initialize when DOM is ready (works with both jQuery and vanilla JS)
    (function() {
        function initTabs() {
            // Check if Bootstrap 5 is available
            const isBootstrap5 = typeof bootstrap !== 'undefined';
            
            // Tab switching - works with both Bootstrap 4 and 5
            const tabList = document.querySelectorAll('#appointmentTabs a[data-bs-toggle="tab"]');
            tabList.forEach(function(tab) {
                tab.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    if (isBootstrap5) {
                        // Bootstrap 5 way
                        try {
                            const targetTab = new bootstrap.Tab(this);
                            targetTab.show();
                        } catch(err) {
                            // Fallback if Bootstrap 5 not fully loaded
                            const tabId = this.getAttribute('href').substring(1);
                            switchToTab(tabId);
                        }
                    } else {
                        // Bootstrap 4 way (jQuery)
                        if (typeof $ !== 'undefined' && $.fn.tab) {
                            $(this).tab('show');
                        } else {
                            // Fallback: manual tab switching
                            const tabId = this.getAttribute('href').substring(1);
                            switchToTab(tabId);
                        }
                    }
                });
            });

            // Handle History button click - switch to Past tab
            const historyBtn = document.getElementById('historyBtn');
            if (historyBtn) {
                historyBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    switchToTab('past');
                });
            }

            // Ensure tabs work on page load with hash
            var hash = window.location.hash;
            if (hash) {
                const hashTab = hash.substring(1);
                switchToTab(hashTab);
            }
        }

        // Wait for DOM and scripts to load
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initTabs);
        } else {
            // DOM already loaded
            if (typeof $ !== 'undefined') {
                $(document).ready(initTabs);
            } else {
                initTabs();
            }
        }
    })();
</script>
@endpush
@endsection
