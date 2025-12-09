@extends('web.layouts.dashboard_master')

@section('title', 'Patient Management')
@section('header_title', 'Patients')

@section('content')
<div class="card border-0 shadow-sm" style="border-radius: 15px;">
    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
        <h5 class="mb-0 font-weight-bold">{{ __('All Patients') }}</h5>
        <div>
             <a href="{{ route('clinic.patients.create') }}" class="btn btn-primary btn-sm px-3" style="background-color: #00897b; border-color: #00897b;">
                <i class="las la-plus"></i> {{ __('Add New Patient') }}
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Search & Filter -->
        <form action="{{ route('clinic.patients.index') }}" method="GET" class="mb-4">
            <div class="form-row">
                <div class="col-md-4">
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text bg-light border-right-0"><i class="las la-search"></i></span>
                        </div>
                        <input type="text" name="search" class="form-control border-left-0 bg-light" placeholder="Search by name, phone, email..." value="{{ request('search') }}">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="status" class="form-control bg-light" onchange="this.form.submit()">
                        <option value="">{{ __('All Statuses') }}</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('Active') }}</option>
                        <option value="discharged" {{ request('status') == 'discharged' ? 'selected' : '' }}>{{ __('Discharged') }}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-light btn-block">{{ __('Filter') }}</button>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0">{{ __('Name') }}</th>
                        <th class="border-0">{{ __('Phone') }}</th>
                        <th class="border-0">{{ __('Age/Gender') }}</th>
                        <th class="border-0">{{ __('Last Visit') }}</th>
                        <th class="border-0">{{ __('Status') }}</th>
                        <th class="border-0 text-right">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($patients as $patient)
                    <tr>
                        <td class="align-middle">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary text-white rounded-circle mr-3 d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; background-color: #e0f2f1 !important; color: #00897b !important;">
                                    {{ substr($patient->first_name, 0, 1) }}
                                </div>
                                <div>
                                    <a href="{{ route('clinic.patients.show', $patient->id) }}" class="text-dark font-weight-bold">{{ $patient->first_name }} {{ $patient->last_name }}</a>
                                    <div class="text-muted small">ID: #{{ $patient->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="align-middle">{{ $patient->phone }}</td>
                        <td class="align-middle">
                            {{ \Carbon\Carbon::parse($patient->dob)->age }} {{ __('Yrs') }}
                            <span class="text-muted text-capitalize">/ {{ $patient->gender ?? '-' }}</span>
                        </td>
                        <td class="align-middle text-muted">
                            {{-- $patient->appointments()->latest()->first()->start_time ?? '-' --}}
                            Dec 12, 2024
                        </td>
                        <td class="align-middle">
                            @if($patient->status == 'active')
                                <span class="badge badge-success px-2 py-1">{{ __('Active') }}</span>
                            @else
                                <span class="badge badge-secondary px-2 py-1">{{ ucfirst($patient->status) }}</span>
                            @endif
                        </td>
                        <td class="align-middle text-right">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-link text-muted" data-toggle="dropdown"><i class="las la-ellipsis-h" style="font-size: 1.2rem;"></i></button>
                                <div class="dropdown-menu dropdown-menu-right border-0 shadow">
                                    <a class="dropdown-item" href="{{ route('clinic.patients.show', $patient->id) }}"><i class="las la-eye mr-2"></i> {{ __('View Profile') }}</a>
                                    <a class="dropdown-item" href="{{ route('clinic.patients.edit', $patient->id) }}"><i class="las la-edit mr-2"></i> {{ __('Edit Details') }}</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item" href="#"><i class="las la-calendar-plus mr-2"></i> {{ __('Book Appointment') }}</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="las la-user-injured mb-2" style="font-size: 3rem; opacity: 0.5;"></i>
                            <p>{{ __('No patients found.') }}</p>
                            <a href="{{ route('clinic.patients.create') }}" class="btn btn-sm btn-outline-primary">{{ __('Register New Patient') }}</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center mt-3">
            {{ $patients->links() }}
        </div>
    </div>
</div>
@endsection
