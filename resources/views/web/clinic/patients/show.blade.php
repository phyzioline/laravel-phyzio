@extends('web.layouts.dashboard_master')

@section('title', $patient->first_name . ' ' . $patient->last_name)
@section('header_title', 'Patient Profile')

@section('content')
<div class="row">
    <!-- Sidebar: Patient Card -->
    <div class="col-lg-3 mb-4">
        <div class="card border-0 shadow-sm text-center py-4" style="border-radius: 15px;">
            <div class="card-body">
                <div class="bg-light rounded-circle mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 100px; height: 100px; font-size: 2.5rem; color: #00897b;">
                    {{ substr($patient->first_name, 0, 1) }}
                </div>
                <h5 class="font-weight-bold mb-0">{{ $patient->first_name }} {{ $patient->last_name }}</h5>
                <div class="text-muted small mb-3">ID: #{{ $patient->id }}</div>
                
                @if($patient->status == 'active')
                    <span class="badge badge-success px-3 py-1 mb-3">{{ __('Active Patient') }}</span>
                @else
                    <span class="badge badge-secondary px-3 py-1 mb-3">{{ ucfirst($patient->status) }}</span>
                @endif
                
                <div class="text-left mt-4 px-2">
                    <p class="mb-2"><i class="las la-phone text-muted mr-2"></i> {{ $patient->phone }}</p>
                    <p class="mb-2"><i class="las la-envelope text-muted mr-2"></i> {{ $patient->email ?? 'N/A' }}</p>
                    <p class="mb-2"><i class="las la-birthday-cake text-muted mr-2"></i> {{ \Carbon\Carbon::parse($patient->dob)->age }} Yrs ({{ ucfirst($patient->gender) }})</p>
                    <p class="mb-0"><i class="las la-map-marker text-muted mr-2"></i> {{ $patient->address ?? 'N/A' }}</p>
                </div>
            </div>
            <div class="card-footer bg-white border-0">
                <a href="{{ route('clinic.patients.edit', $patient->id) }}" class="btn btn-outline-secondary btn-block btn-sm">{{ __('Edit Profile') }}</a>
            </div>
        </div>

        <div class="card border-0 shadow-sm mt-3" style="border-radius: 15px;">
            <div class="card-header bg-white font-weight-bold border-0">{{ __('Medical Alert') }}</div>
            <div class="card-body text-danger small pt-0">
                {{ $patient->medical_history ?? 'No known allergies or conditions.' }}
            </div>
        </div>
    </div>

    <!-- Main Content: Tabs -->
    <div class="col-lg-9">
        <div class="card border-0 shadow-sm" style="border-radius: 15px; min-height: 500px;">
            <div class="card-header bg-white border-0">
                <ul class="nav nav-pills card-header-pills" id="patientTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="appointments-tab" data-toggle="tab" href="#appointments" role="tab">{{ __('Appointments') }}</a>
                    </li>
                    <li class="nav-item">
                         <a class="nav-link" id="plans-tab" data-toggle="tab" href="#plans" role="tab">{{ __('Treatment Plans') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="notes-tab" data-toggle="tab" href="#notes" role="tab">{{ __('Session Notes') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="billing-tab" data-toggle="tab" href="#billing" role="tab">{{ __('Billing & Invoices') }}</a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="patientTabsContent">
                    <!-- Appointments Tab -->
                    <div class="tab-pane fade show active" id="appointments" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="font-weight-bold">{{ __('Appointment History') }}</h6>
                            <button class="btn btn-sm btn-primary" style="background-color: #00897b;"><i class="las la-plus"></i> {{ __('Book New') }}</button>
                        </div>
                         @if($appointments->isEmpty())
                            <div class="text-center text-muted py-4">No appointments found.</div>
                         @else
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Type</th>
                                            <th>Therapist</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($appointments as $appt)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($appt->start_time)->format('M d, Y H:i') }}</td>
                                            <td>{{ ucfirst($appt->type) }}</td>
                                            <td>{{ $appt->therapist->name ?? '-' }}</td>
                                            <td>{{ ucfirst($appt->status) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                         @endif
                    </div>

                    <!-- Treatment Plans Tab -->
                    <div class="tab-pane fade" id="plans" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="font-weight-bold">{{ __('Active Treatment Plans') }}</h6>
                            <button class="btn btn-sm btn-primary" style="background-color: #00897b;"><i class="las la-plus"></i> {{ __('Create Plan') }}</button>
                        </div>
                        @if($treatmentPlans->isEmpty())
                            <div class="text-center text-muted py-4">No active treatment plans.</div>
                        @else
                            @foreach($treatmentPlans as $plan)
                                <div class="card mb-3 border bg-light">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between">
                                            <h6 class="font-weight-bold">{{ $plan->diagnosis }}</h6>
                                            <span class="badge badge-success">{{ ucfirst($plan->status) }}</span>
                                        </div>
                                        <div class="small text-muted mb-2">Started: {{ $plan->created_at->format('M d, Y') }}</div>
                                        <p class="mb-1 small">{{ strip_tags($plan->notes) }}</p>
                                        <div class="mt-2 text-right">
                                            <button class="btn btn-sm btn-outline-info">{{ __('Track Progress') }}</button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <!-- Notes Tab -->
                    <div class="tab-pane fade" id="notes" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="font-weight-bold">{{ __('Clinical Documentation') }}</h6>
                            <button class="btn btn-sm btn-primary" style="background-color: #00897b;"><i class="las la-plus"></i> {{ __('Add SOAP Note') }}</button>
                        </div>
                        <div class="text-center text-muted py-4">Select an appointment to view session notes.</div>
                    </div>

                    <!-- Billing Tab -->
                    <div class="tab-pane fade" id="billing" role="tabpanel">
                         <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="font-weight-bold">{{ __('Invoices & Payments') }}</h6>
                            <button class="btn btn-sm btn-primary" style="background-color: #00897b;"><i class="las la-file-invoice-dollar"></i> {{ __('New Invoice') }}</button>
                        </div>
                         @if($invoices->isEmpty())
                            <div class="text-center text-muted py-4">No invoices found.</div>
                         @else
                            <!-- Invoice List Mock -->
                            <p>Invoice #101 - Paid</p>
                         @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
