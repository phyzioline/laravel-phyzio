@extends('therapist.layouts.app')

@section('content')
<div class="container-fluid py-4" style="background-color: #f8f9fa;">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="font-weight-bold text-dark mb-0">{{ __('Appointments') }}</h2>
            <p class="text-muted">{{ __('View and manage your consultation schedule') }}</p>
        </div>
        <div>
             <button class="btn btn-outline-secondary mr-2 shadow-sm"><i class="las la-history"></i> {{ __('History') }}</button>
             <button class="btn btn-primary shadow-sm"><i class="las la-plus"></i> {{ __('New Appointment') }}</button>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row mb-4">
        <div class="col-md-3">
             <div class="card shadow-sm border-0 border-left-primary py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">{{ __('Total Appointments') }}</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">1,254</div>
                        </div>
                        <div class="col-auto">
                            <i class="las la-calendar fa-2x text-gray-300"></i>
                        </div>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">1,100</div>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">45</div>
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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">20</div>
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
                    <a class="nav-link active" id="upcoming-tab" data-toggle="pill" href="#upcoming" role="tab" aria-controls="upcoming" aria-selected="true">{{ __('Upcoming') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="past-tab" data-toggle="pill" href="#past" role="tab" aria-controls="past" aria-selected="false">{{ __('Past') }}</a>
                </li>
                 <li class="nav-item">
                    <a class="nav-link" id="cancelled-tab" data-toggle="pill" href="#cancelled" role="tab" aria-controls="cancelled" aria-selected="false">{{ __('Cancelled') }}</a>
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
                                <!-- Mock Data Row 1 -->
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-primary text-white rounded-circle mr-2 d-flex align-items-center justify-content-center font-weight-bold" style="width: 35px; height: 35px;">JD</div>
                                            <span class="font-weight-bold">John Doe</span>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-light border">{{ __('Video Call') }}</span></td>
                                    <td>Dec 23, 2024</td>
                                    <td>10:00 AM</td>
                                    <td><span class="badge badge-warning">{{ __('Scheduled') }}</span></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-success shadow-sm" title="Start Call"><i class="las la-video"></i> {{ __('Join') }}</button>
                                        <button class="btn btn-sm btn-light border shadow-sm" title="View Details"><i class="las la-eye"></i></button>
                                        <button class="btn btn-sm btn-light border shadow-sm text-danger" title="Cancel"><i class="las la-times"></i></button>
                                    </td>
                                </tr>
                                 <!-- Mock Data Row 2 -->
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle bg-info text-white rounded-circle mr-2 d-flex align-items-center justify-content-center font-weight-bold" style="width: 35px; height: 35px;">SM</div>
                                            <span class="font-weight-bold">Sarah Miller</span>
                                        </div>
                                    </td>
                                    <td><span class="badge badge-light border">{{ __('In-Person') }}</span></td>
                                    <td>Dec 23, 2024</td>
                                    <td>2:00 PM</td>
                                    <td><span class="badge badge-primary">{{ __('Confirmed') }}</span></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-light border shadow-sm" title="Check-in"><i class="las la-check"></i> {{ __('Check-in') }}</button>
                                        <button class="btn btn-sm btn-light border shadow-sm" title="View Details"><i class="las la-eye"></i></button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Past Tab -->
                <div class="tab-pane fade" id="past" role="tabpanel" aria-labelledby="past-tab">
                     <p class="text-center text-muted p-4">No recent past appointments showing.</p>
                </div>
                 <!-- Cancelled Tab -->
                <div class="tab-pane fade" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">
                     <p class="text-center text-muted p-4">No cancelled appointments showing.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
