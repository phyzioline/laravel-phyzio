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
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $appointments->count() }}</div>
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
                                    <td><span class="badge badge-light border">{{ $appointment->service->name ?? $appointment->type }}</span></td>
                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</td>
                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A') }}</td>
                                    <td><span class="badge badge-warning">{{ ucfirst($appointment->status) }}</span></td>
                                    <td class="text-center">
                                        <a href="{{ route('therapist.appointments.show', $appointment->id) }}" class="btn btn-sm btn-light border shadow-sm" title="View Details"><i class="las la-eye"></i></a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        <div class="text-gray-500">No upcoming appointments found.</div>
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
                                    <td>{{ $appointment->patient->name ?? 'Unknown' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</td>
                                    <td><span class="badge badge-light border">{{ ucfirst($appointment->status) }}</span></td>
                                    <td class="text-center">
                                        <button class="btn btn-sm btn-light border shadow-sm"><i class="las la-eye"></i></button>
                                    </td>
                                </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center">No past appointments.</td></tr>
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
                                    <td>{{ $appointment->patient->name ?? 'Unknown' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}</td>
                                    <td>{{ $appointment->notes ?? 'No reason provided' }}</td>
                                </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center">No cancelled appointments.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
