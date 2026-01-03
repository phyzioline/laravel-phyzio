@extends('web.layouts.dashboard_master')

@section('title', 'Assign Doctors to Slots')
@section('header_title', 'Intensive Session - Doctor Assignment')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title font-weight-bold mb-1">{{ __('Intensive Session Details') }}</h5>
                        <p class="text-muted mb-0">
                            <strong>{{ __('Patient') }}:</strong> {{ $appointment->patient->first_name }} {{ $appointment->patient->last_name }} |
                            <strong>{{ __('Date') }}:</strong> {{ $appointment->appointment_date->format('M d, Y') }} |
                            <strong>{{ __('Time') }}:</strong> {{ $appointment->appointment_date->format('h:i A') }} |
                            <strong>{{ __('Duration') }}:</strong> {{ $appointment->total_hours }} {{ __('hours') }}
                        </p>
                    </div>
                    <a href="{{ route('clinic.appointments.index') }}" class="btn btn-secondary">
                        <i class="las la-arrow-left"></i> {{ __('Back to Calendar') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Slots Timeline -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="font-weight-bold mb-0">
                    <i class="las la-clock"></i> {{ __('Time Slots') }}
                </h5>
                <p class="text-muted small mb-0">{{ __('Drag doctors to slots or click to assign') }}</p>
            </div>
            <div class="card-body px-4">
                <div id="slotsContainer">
                    @foreach($slots as $slot)
                    <div class="slot-card mb-3 border rounded p-3" data-slot-id="{{ $slot->id }}" 
                         style="min-height: 100px; background: {{ $slot->status === 'assigned' ? '#e8f5e9' : '#fff3e0' }};">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <div class="d-flex align-items-center mb-2">
                                    <span class="badge badge-primary mr-2">{{ __('Slot') }} {{ $slot->slot_number }}</span>
                                    <strong>{{ $slot->slot_start_time->format('h:i A') }} - {{ $slot->slot_end_time->format('h:i A') }}</strong>
                                </div>
                                
                                <div id="slot-{{ $slot->id }}-assignment" class="mt-2">
                                    @if($slot->assignedDoctor)
                                        @php $assignment = $slot->assignedDoctor; @endphp
                                        <div class="assigned-doctor-card p-2 bg-white rounded border">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>{{ $assignment->doctor->name }}</strong>
                                                    <small class="d-block text-muted">
                                                        {{ __('Hourly Rate') }}: ${{ number_format($assignment->doctor->hourlyRate->hourly_rate ?? 0, 2) }}
                                                    </small>
                                                </div>
                                                <button class="btn btn-sm btn-danger unassign-doctor" 
                                                        data-slot-id="{{ $slot->id }}"
                                                        data-appointment-id="{{ $appointment->id }}">
                                                    <i class="las la-times"></i> {{ __('Remove') }}
                                                </button>
                                            </div>
                                        </div>
                                    @else
                                        <div class="empty-slot text-center p-3 border-dashed rounded" 
                                             style="border: 2px dashed #ccc; cursor: pointer;"
                                             data-slot-id="{{ $slot->id }}"
                                             onclick="showDoctorSelector({{ $slot->id }})">
                                            <i class="las la-user-plus fa-2x text-muted mb-2"></i>
                                            <p class="text-muted mb-0">{{ __('Click to assign doctor') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                @if($slots->where('status', 'pending')->count() > 0)
                <div class="alert alert-warning mt-3">
                    <i class="las la-exclamation-triangle"></i> 
                    {{ __('Warning') }}: {{ $slots->where('status', 'pending')->count() }} {{ __('slot(s) are still unassigned') }}
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Available Doctors Sidebar -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm sticky-top" style="top: 20px; border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="font-weight-bold mb-0">
                    <i class="las la-user-md"></i> {{ __('Available Doctors') }}
                </h5>
                <p class="text-muted small mb-0">{{ __('Pediatric specialists') }}</p>
            </div>
            <div class="card-body px-4" id="availableDoctorsList">
                <div class="text-center py-4">
                    <i class="las la-spinner fa-spin fa-2x text-muted"></i>
                    <p class="text-muted">{{ __('Loading doctors...') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Doctor Selection Modal -->
<div class="modal fade" id="doctorSelectorModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Select Doctor for Slot') }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body" id="doctorSelectorContent">
                <!-- Doctors will be loaded here -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
let currentSlotId = null;

function showDoctorSelector(slotId) {
    currentSlotId = slotId;
    const modal = $('#doctorSelectorModal');
    const content = $('#doctorSelectorContent');
    
    content.html('<div class="text-center py-4"><i class="las la-spinner fa-spin fa-2x"></i></div>');
    modal.modal('show');
    
    // Load available doctors for this slot
    fetch(`{{ route('clinic.appointments.availableDoctorsForSlot', [$appointment->id, 'SLOT_ID']) }}`.replace('SLOT_ID', slotId), {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.doctors.length > 0) {
            let html = '<div class="list-group">';
            data.doctors.forEach(doctor => {
                html += `
                    <a href="#" class="list-group-item list-group-item-action" onclick="assignDoctor(${slotId}, ${doctor.id}); return false;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong>${doctor.name}</strong>
                                <small class="d-block text-muted">${doctor.specialty || 'General'}</small>
                            </div>
                            <div class="text-right">
                                <strong class="text-primary">$${parseFloat(doctor.hourly_rate).toFixed(2)}</strong>
                                <small class="d-block text-muted">/hour</small>
                            </div>
                        </div>
                    </a>
                `;
            });
            html += '</div>';
            content.html(html);
        } else {
            content.html('<div class="alert alert-warning">No available doctors for this slot.</div>');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        content.html('<div class="alert alert-danger">Error loading doctors.</div>');
    });
}

function assignDoctor(slotId, doctorId) {
    const slotCard = $(`#slot-${slotId}-assignment`);
    slotCard.html('<div class="text-center py-2"><i class="las la-spinner fa-spin"></i> Assigning...</div>');
    
    fetch(`{{ route('clinic.appointments.assignSlot', [$appointment->id, 'SLOT_ID']) }}`.replace('SLOT_ID', slotId), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ doctor_id: doctorId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $('#doctorSelectorModal').modal('hide');
            location.reload(); // Reload to show updated assignment
        } else {
            alert(data.message || 'Error assigning doctor');
            location.reload();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Network error. Please try again.');
        location.reload();
    });
}

// Unassign doctor
$(document).on('click', '.unassign-doctor', function(e) {
    e.preventDefault();
    const slotId = $(this).data('slot-id');
    const appointmentId = $(this).data('appointment-id');
    
    if (!confirm('Are you sure you want to remove this doctor from the slot?')) {
        return;
    }
    
    fetch(`{{ route('clinic.appointments.unassignSlot', [$appointment->id, 'SLOT_ID']) }}`.replace('SLOT_ID', slotId), {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert(data.message || 'Error removing doctor');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Network error. Please try again.');
    });
});

// Load available doctors list on page load
document.addEventListener('DOMContentLoaded', function() {
    // For now, show a message - can be enhanced to show all clinic doctors
    $('#availableDoctorsList').html(`
        <div class="text-center py-4">
            <i class="las la-info-circle fa-2x text-muted mb-2"></i>
            <p class="text-muted">Click on a slot to see available doctors</p>
        </div>
    `);
});
</script>
@endpush
@endsection

