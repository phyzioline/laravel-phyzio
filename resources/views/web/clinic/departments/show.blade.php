@extends('web.layouts.dashboard_master')

@section('title', $displayName . ' - Department Details')
@section('header_title', $displayName . ' {{ __("Department") }}')

@section('content')
<div class="row mb-4">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h4 class="font-weight-bold mb-1">{{ $displayName }}</h4>
                <p class="text-muted mb-0">{{ __('Department details and assigned doctors') }}</p>
            </div>
            <a href="{{ route('clinic.departments.index') }}" class="btn btn-secondary">
                <i class="las la-arrow-left"></i> {{ __('Back to Departments') }}
            </a>
        </div>
    </div>
</div>

<!-- Statistics -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center" style="border-radius: 15px;">
            <div class="card-body">
                <i class="las la-user-md fa-2x text-primary mb-2"></i>
                <h3 class="font-weight-bold mb-0">{{ $assignedDoctors->count() }}</h3>
                <small class="text-muted">{{ __('Assigned Doctors') }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center" style="border-radius: 15px;">
            <div class="card-body">
                <i class="las la-calendar-check fa-2x text-success mb-2"></i>
                <h3 class="font-weight-bold mb-0">{{ $totalAppointments }}</h3>
                <small class="text-muted">{{ __('Total Appointments') }}</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm text-center" style="border-radius: 15px;">
            <div class="card-body">
                <i class="las la-user-injured fa-2x text-info mb-2"></i>
                <h3 class="font-weight-bold mb-0">{{ $totalPatients }}</h3>
                <small class="text-muted">{{ __('Total Patients') }}</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Assigned Doctors -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="font-weight-bold mb-0">
                        <i class="las la-user-md"></i> {{ __('Assigned Doctors') }}
                    </h5>
                    <span class="badge badge-primary">{{ $assignedDoctors->count() }} {{ __('doctors') }}</span>
                </div>
            </div>
            <div class="card-body px-4">
                @if($assignedDoctors->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>{{ __('Doctor') }}</th>
                                <th>{{ __('Specialization') }}</th>
                                <th>{{ __('Role') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($assignedDoctors as $assignment)
                            @php $doctor = $assignment->doctor; @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary text-white rounded-circle mr-2 d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px;">
                                            {{ substr($doctor->name ?? 'N/A', 0, 1) }}
                                        </div>
                                        <div>
                                            <strong>{{ $doctor->name ?? 'N/A' }}</strong>
                                            <small class="d-block text-muted">{{ $doctor->email ?? '' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $doctor->specialization ?? __('General') }}</td>
                                <td>
                                    @if($assignment->is_head)
                                        <span class="badge badge-warning">{{ __('Head of Department') }}</span>
                                    @else
                                        <span class="badge badge-secondary">{{ __('Doctor') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $todayAppointments = \App\Models\ClinicAppointment::where('clinic_id', $clinic->id)
                                            ->where('doctor_id', $doctor->id)
                                            ->whereDate('appointment_date', today())
                                            ->where('status', '!=', 'cancelled')
                                            ->count();
                                        $status = $todayAppointments > 0 ? 'Busy' : 'Available';
                                    @endphp
                                    <span class="badge {{ $status === 'Busy' ? 'badge-danger' : 'badge-success' }}">
                                        {{ __($status) }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-danger unassign-doctor" 
                                            data-doctor-id="{{ $doctor->id }}"
                                            data-specialty="{{ $specialty }}">
                                        <i class="las la-times"></i> {{ __('Remove') }}
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="text-center py-5">
                    <i class="las la-user-slash fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">{{ __('No doctors assigned') }}</h5>
                    <p class="text-muted">{{ __('Assign doctors to this department below.') }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Assign New Doctor -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm sticky-top" style="top: 20px; border-radius: 15px;">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="font-weight-bold mb-0">
                    <i class="las la-user-plus"></i> {{ __('Assign Doctor') }}
                </h5>
            </div>
            <div class="card-body px-4">
                @if($availableDoctors->count() > 0)
                <form id="assignDoctorForm">
                    @csrf
                    <div class="form-group">
                        <label>{{ __('Select Doctor') }}</label>
                        <select name="doctor_id" class="form-control" required>
                            <option value="">{{ __('Choose Doctor') }}</option>
                            @foreach($availableDoctors as $doctor)
                                <option value="{{ $doctor->id }}">
                                    {{ $doctor->name }} 
                                    @if($doctor->specialization)
                                        ({{ $doctor->specialization }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="is_head" name="is_head" value="1">
                            <label class="custom-control-label" for="is_head">
                                {{ __('Set as Head of Department') }}
                            </label>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>{{ __('Priority') }} <small class="text-muted">({{ __('0-100') }})</small></label>
                        <input type="number" name="priority" class="form-control" min="0" max="100" value="0">
                        <small class="text-muted">{{ __('Higher priority appears first') }}</small>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="las la-plus"></i> {{ __('Assign Doctor') }}
                    </button>
                </form>
                @else
                <div class="text-center py-4">
                    <i class="las la-info-circle fa-2x text-muted mb-2"></i>
                    <p class="text-muted">{{ __('All available doctors are already assigned to this department.') }}</p>
                    <a href="{{ route('clinic.doctors.create') }}" class="btn btn-sm btn-outline-primary">
                        {{ __('Add New Doctor') }}
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Assign doctor form
    const assignForm = document.getElementById('assignDoctorForm');
    if (assignForm) {
        assignForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(assignForm);
            const data = {
                doctor_id: formData.get('doctor_id'),
                is_head: formData.get('is_head') === '1',
                priority: parseInt(formData.get('priority')) || 0
            };
            
            fetch(`{{ route('clinic.departments.assignDoctor', $specialty) }}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    location.reload();
                } else {
                    alert(result.message || '{{ __("Error assigning doctor") }}');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Network error. Please try again.');
            });
        });
    }
    
    // Unassign doctor
    document.querySelectorAll('.unassign-doctor').forEach(btn => {
        btn.addEventListener('click', function() {
            if (!confirm('{{ __("Are you sure you want to remove this doctor from this department?") }}')) {
                return;
            }
            
            const doctorId = this.dataset.doctorId;
            const specialty = this.dataset.specialty;
            
            fetch(`{{ route('clinic.departments.unassignDoctor', [$specialty, 'DOCTOR_ID']) }}`.replace('DOCTOR_ID', doctorId), {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(result => {
                if (result.success) {
                    location.reload();
                } else {
                    alert(result.message || '{{ __("Error removing doctor") }}');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Network error. Please try again.');
            });
        });
    });
});
</script>
@endpush
@endsection

