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

        <!-- Medical History Card -->
        <div class="card border-0 shadow-sm mt-3" style="border-radius: 15px;">
            <div class="card-header bg-white font-weight-bold border-0">{{ __('Medical History') }}</div>
            <div class="card-body small pt-0">
                <div class="mb-2">
                    <strong>{{ __('History:') }}</strong>
                    <p class="mb-0">{{ $patient->medical_history ?? 'No medical history recorded.' }}</p>
                </div>
                @if(isset($patient->primary_condition) && $patient->primary_condition)
                <div class="mb-2">
                    <strong>{{ __('Primary Condition:') }}</strong>
                    <p class="mb-0">{{ $patient->primary_condition }}</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Financial Summary Card -->
        <div class="card border-0 shadow-sm mt-3" style="border-radius: 15px;">
            <div class="card-header bg-white font-weight-bold border-0">{{ __('Payments & Balance') }}</div>
            <div class="card-body pt-0">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="text-muted small">{{ __('Total Invoiced') }}</div>
                        <div class="h5 font-weight-bold text-primary">${{ number_format($patient->total_invoiced ?? 0, 2) }}</div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="text-muted small">{{ __('Total Paid') }}</div>
                        <div class="h5 font-weight-bold text-success">${{ number_format($patient->total_paid ?? 0, 2) }}</div>
                    </div>
                    <div class="col-12">
                        <div class="text-muted small">{{ __('Remaining Balance') }}</div>
                        <div class="h4 font-weight-bold {{ ($patient->remaining_balance ?? 0) > 0 ? 'text-danger' : 'text-success' }}">
                            ${{ number_format($patient->remaining_balance ?? 0, 2) }}
                        </div>
                    </div>
                </div>
                @if(($patient->remaining_balance ?? 0) > 0)
                <div class="mt-3">
                    <a href="{{ route('clinic.payments.create', ['patient_id' => $patient->id]) }}" class="btn btn-sm btn-primary btn-block">
                        <i class="las la-money-bill-wave"></i> {{ __('Record Payment') }}
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Main Content: Tabs -->
    <div class="col-lg-9">
        <div class="card border-0 shadow-sm" style="border-radius: 15px; min-height: 500px;">
            <div class="card-header bg-white border-0">
                <ul class="nav nav-pills card-header-pills" id="patientTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="timeline-tab" data-toggle="tab" href="#timeline" role="tab">
                            <i class="las la-history"></i> {{ __('Session Timeline') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="appointments-tab" data-toggle="tab" href="#appointments" role="tab">
                            <i class="las la-calendar"></i> {{ __('Appointments') }}
                        </a>
                    </li>
                    <li class="nav-item">
                         <a class="nav-link" id="plans-tab" data-toggle="tab" href="#plans" role="tab">
                            <i class="las la-clipboard-list"></i> {{ __('Treatment Plans') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="notes-tab" data-toggle="tab" href="#notes" role="tab">
                            <i class="las la-sticky-note"></i> {{ __('Session Notes') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="billing-tab" data-toggle="tab" href="#billing" role="tab">
                            <i class="las la-file-invoice-dollar"></i> {{ __('Billing & Payments') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="attachments-tab" data-toggle="tab" href="#attachments" role="tab">
                            <i class="las la-paperclip"></i> {{ __('Attachments') }}
                        </a>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="patientTabsContent">
                    <!-- Session Timeline Tab (NEW - Chronological) -->
                    <div class="tab-pane fade show active" id="timeline" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="font-weight-bold">{{ __('Session Timeline') }} <small class="text-muted">(Chronological)</small></h6>
                            <a href="{{ route('clinic.appointments.create', ['patient_id' => $patient->id]) }}" class="btn btn-sm btn-primary" style="background-color: #00897b;">
                                <i class="las la-plus"></i> {{ __('Add Session') }}
                            </a>
                        </div>
                        @if(isset($sessionsTimeline) && $sessionsTimeline->isNotEmpty())
                            <div class="timeline">
                                @foreach($sessionsTimeline as $item)
                                <div class="timeline-item mb-4 pb-3 border-left pl-4 position-relative">
                                    <div class="timeline-marker position-absolute" style="left: -6px; top: 0; width: 12px; height: 12px; background: #00897b; border-radius: 50%;"></div>
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <h6 class="font-weight-bold mb-1">{{ $item->title }}</h6>
                                            <div class="small text-muted mb-2">
                                                <i class="las la-calendar"></i> 
                                                {{ $item->date instanceof \Carbon\Carbon ? $item->date->format('M d, Y') : \Carbon\Carbon::parse($item->date)->format('M d, Y') }}
                                                @if($item->type === 'appointment' && isset($item->data->appointment_date))
                                                    • {{ $item->data->appointment_date->format('h:i A') }}
                                                @endif
                                            </div>
                                            @if($item->type === 'appointment' && isset($item->data->notes))
                                                <p class="small mb-0">{{ Str::limit($item->data->notes, 100) }}</p>
                                            @endif
                                        </div>
                                        <div>
                                            <span class="badge badge-{{ $item->status === 'completed' ? 'success' : ($item->status === 'cancelled' ? 'danger' : 'primary') }}">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-5">
                                <i class="las la-history fa-3x mb-3"></i>
                                <p>{{ __('No sessions recorded yet.') }}</p>
                                <a href="{{ route('clinic.appointments.create', ['patient_id' => $patient->id]) }}" class="btn btn-primary">
                                    <i class="las la-plus"></i> {{ __('Schedule First Session') }}
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Appointments Tab -->
                    <div class="tab-pane fade" id="appointments" role="tabpanel">
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
                            <a href="{{ route('clinic.plans.create', ['patient_id' => $patient->id]) }}" class="btn btn-sm btn-primary" style="background-color: #00897b;"><i class="las la-plus"></i> {{ __('Create Plan') }}</a>
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
                            <a href="{{ route('clinic.clinical-notes.create', ['patient_id' => $patient->id]) }}" class="btn btn-sm btn-primary" style="background-color: #00897b;"><i class="las la-plus"></i> {{ __('Add SOAP Note') }}</a>
                        </div>
                        @php
                            // Get clinical notes for this patient
                            $clinicalNotes = \App\Models\ClinicalNote::where('patient_id', $patient->id)
                                ->with(['appointment', 'episode'])
                                ->latest()
                                ->get();
                        @endphp
                        @if($clinicalNotes->isEmpty())
                            <div class="text-center text-muted py-4">No clinical notes found. Create a SOAP note to document patient sessions.</div>
                        @else
                            <div class="list-group">
                                @foreach($clinicalNotes as $note)
                                    <div class="list-group-item mb-2 border rounded">
                                        <div class="d-flex justify-content-between align-items-start">
                                            <div>
                                                <h6 class="mb-1">{{ $note->created_at->format('M d, Y H:i') }}</h6>
                                                @if($note->appointment)
                                                    <small class="text-muted">Appointment: {{ $note->appointment->appointment_date ?? 'N/A' }}</small>
                                                @endif
                                                @if($note->episode)
                                                    <small class="text-muted">Episode: {{ $note->episode->title ?? 'N/A' }}</small>
                                                @endif
                                            </div>
                                            <a href="{{ route('clinic.clinical-notes.show', $note->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                        </div>
                                        @if($note->subjective)
                                            <p class="mb-1 small"><strong>S:</strong> {{ Str::limit($note->subjective, 100) }}</p>
                                        @endif
                                        @if($note->objective)
                                            <p class="mb-1 small"><strong>O:</strong> {{ Str::limit($note->objective, 100) }}</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Billing Tab (Enhanced) -->
                    <div class="tab-pane fade" id="billing" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="font-weight-bold">{{ __('Billing & Payments') }}</h6>
                            <div>
                                <a href="{{ route('clinic.invoices.create', ['patient_id' => $patient->id]) }}" class="btn btn-sm btn-primary mr-2" style="background-color: #00897b;">
                                    <i class="las la-file-invoice-dollar"></i> {{ __('New Invoice') }}
                                </a>
                                <a href="{{ route('clinic.payments.create', ['patient_id' => $patient->id]) }}" class="btn btn-sm btn-success">
                                    <i class="las la-money-bill-wave"></i> {{ __('Record Payment') }}
                                </a>
                            </div>
                        </div>

                        <!-- Financial Summary -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="card bg-primary text-white">
                                    <div class="card-body text-center">
                                        <div class="small mb-1">{{ __('Total Invoiced') }}</div>
                                        <div class="h4 font-weight-bold">${{ number_format($patient->total_invoiced ?? 0, 2) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-success text-white">
                                    <div class="card-body text-center">
                                        <div class="small mb-1">{{ __('Total Paid') }}</div>
                                        <div class="h4 font-weight-bold">${{ number_format($patient->total_paid ?? 0, 2) }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card {{ ($patient->remaining_balance ?? 0) > 0 ? 'bg-danger' : 'bg-secondary' }} text-white">
                                    <div class="card-body text-center">
                                        <div class="small mb-1">{{ __('Remaining Balance') }}</div>
                                        <div class="h4 font-weight-bold">${{ number_format($patient->remaining_balance ?? 0, 2) }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Invoices -->
                        <h6 class="font-weight-bold mb-3">{{ __('Invoices') }}</h6>
                        @if(isset($invoices) && $invoices->isNotEmpty())
                            <div class="table-responsive mb-4">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Invoice #') }}</th>
                                            <th>{{ __('Date') }}</th>
                                            <th>{{ __('Amount') }}</th>
                                            <th>{{ __('Paid') }}</th>
                                            <th>{{ __('Balance') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($invoices as $invoice)
                                        <tr>
                                            <td><strong>{{ $invoice->invoice_number }}</strong></td>
                                            <td>{{ $invoice->invoice_date->format('M d, Y') }}</td>
                                            <td>${{ number_format($invoice->final_amount, 2) }}</td>
                                            <td>${{ number_format($invoice->total_paid ?? 0, 2) }}</td>
                                            <td>
                                                <strong class="{{ ($invoice->remaining_balance ?? 0) > 0 ? 'text-danger' : 'text-success' }}">
                                                    ${{ number_format($invoice->remaining_balance ?? ($invoice->final_amount - ($invoice->total_paid ?? 0)), 2) }}
                                                </strong>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $invoice->status == 'paid' ? 'success' : ($invoice->status == 'partially_paid' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst(str_replace('_', ' ', $invoice->status)) }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('clinic.invoices.show', $invoice->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="las la-eye"></i> {{ __('View') }}
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info mb-4">
                                <i class="las la-info-circle"></i> {{ __('No invoices found.') }}
                            </div>
                        @endif

                        <!-- Recent Payments -->
                        <h6 class="font-weight-bold mb-3">{{ __('Recent Payments') }}</h6>
                        @if(isset($payments) && $payments->isNotEmpty())
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Payment #') }}</th>
                                            <th>{{ __('Date') }}</th>
                                            <th>{{ __('Amount') }}</th>
                                            <th>{{ __('Method') }}</th>
                                            <th>{{ __('Invoice') }}</th>
                                            <th>{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($payments->take(10) as $payment)
                                        <tr>
                                            <td><strong>{{ $payment->payment_number }}</strong></td>
                                            <td>{{ $payment->payment_date->format('M d, Y') }}</td>
                                            <td class="text-success font-weight-bold">${{ number_format($payment->payment_amount, 2) }}</td>
                                            <td>
                                                <span class="badge badge-info">{{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}</span>
                                            </td>
                                            <td>
                                                @if($payment->invoice)
                                                    <a href="{{ route('clinic.invoices.show', $payment->invoice->id) }}" class="text-primary">
                                                        {{ $payment->invoice->invoice_number }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('clinic.payments.show', $payment->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="las la-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="las la-info-circle"></i> {{ __('No payments recorded yet.') }}
                            </div>
                        @endif
                    </div>

                    <!-- Attachments Tab (NEW) -->
                    <div class="tab-pane fade" id="attachments" role="tabpanel">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="font-weight-bold">{{ __('Patient Attachments') }}</h6>
                            <button class="btn btn-sm btn-primary" style="background-color: #00897b;" data-toggle="modal" data-target="#uploadAttachmentModal">
                                <i class="las la-upload"></i> {{ __('Upload File') }}
                            </button>
                        </div>
                        @if(isset($attachments) && $attachments->isNotEmpty())
                            <div class="row">
                                @foreach($attachments as $attachment)
                                <div class="col-md-4 mb-3">
                                    <div class="card border h-100">
                                        <div class="card-body">
                                            <div class="d-flex align-items-start mb-2">
                                                <i class="{{ $attachment->file_icon }} fa-2x text-primary mr-3"></i>
                                                <div class="flex-grow-1">
                                                    <h6 class="font-weight-bold mb-1">{{ $attachment->title ?? $attachment->file_name }}</h6>
                                                    <small class="text-muted d-block">{{ $attachment->file_size_human }}</small>
                                                    @if($attachment->category)
                                                        <span class="badge badge-secondary badge-sm">{{ ucfirst($attachment->category) }}</span>
                                                    @endif
                                                </div>
                                            </div>
                                            @if($attachment->description)
                                                <p class="small text-muted mb-2">{{ Str::limit($attachment->description, 80) }}</p>
                                            @endif
                                            <div class="d-flex justify-content-between align-items-center">
                                                <small class="text-muted">
                                                    <i class="las la-calendar"></i> {{ $attachment->created_at->format('M d, Y') }}
                                                </small>
                                                <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                    <i class="las la-download"></i> {{ __('View') }}
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-5">
                                <i class="las la-paperclip fa-3x mb-3"></i>
                                <p>{{ __('No attachments uploaded yet.') }}</p>
                                <button class="btn btn-primary" data-toggle="modal" data-target="#uploadAttachmentModal">
                                    <i class="las la-upload"></i> {{ __('Upload First File') }}
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Upload Attachment Modal -->
<div class="modal fade" id="uploadAttachmentModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Upload Patient Attachment') }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <form action="{{ route('clinic.patients.attachments.store', $patient->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>{{ __('File') }} <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control" required accept=".pdf,.jpg,.jpeg,.png,.doc,.docx">
                        <small class="text-muted">{{ __('Supported: PDF, Images, Documents (Max 10MB)') }}</small>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Title') }}</label>
                        <input type="text" name="title" class="form-control" placeholder="{{ __('e.g., X-ray Report - Left Knee') }}">
                    </div>
                    <div class="form-group">
                        <label>{{ __('Category') }}</label>
                        <select name="category" class="form-control">
                            <option value="xray">{{ __('X-Ray') }}</option>
                            <option value="mri">{{ __('MRI') }}</option>
                            <option value="lab_report">{{ __('Lab Report') }}</option>
                            <option value="doctor_note">{{ __('Doctor Note') }}</option>
                            <option value="prescription">{{ __('Prescription') }}</option>
                            <option value="insurance">{{ __('Insurance') }}</option>
                            <option value="other">{{ __('Other') }}</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Description') }}</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="{{ __('Optional description...') }}"></textarea>
                    </div>
                    <div class="form-group">
                        <label>{{ __('Document Date') }}</label>
                        <input type="date" name="document_date" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Upload') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
