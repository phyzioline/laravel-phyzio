@extends('web.layouts.dashboard_master')

@section('title', 'Clinical Notes')
@section('header_title', 'Clinical Notes (EMR)')

@section('content')
@if(!$clinic)
<div class="alert alert-warning">
    <i class="las la-exclamation-triangle"></i> {{ __('Please set up your clinic first.') }}
</div>
@else
<!-- Filters -->
<div class="card border-0 shadow-sm mb-4" style="border-radius: 15px;">
    <div class="card-body">
        <form method="GET" action="{{ route('clinic.clinical-notes.index') }}" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">{{ __('Patient') }}</label>
                <select name="patient_id" class="form-control">
                    <option value="">{{ __('All Patients') }}</option>
                    @foreach($patients ?? [] as $patient)
                        <option value="{{ $patient->id }}" {{ request('patient_id') == $patient->id ? 'selected' : '' }}>
                            {{ $patient->first_name }} {{ $patient->last_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">{{ __('Specialty') }}</label>
                <select name="specialty" class="form-control">
                    <option value="">{{ __('All') }}</option>
                    @foreach(\App\Models\ClinicalNote::SPECIALTIES as $key => $label)
                        <option value="{{ $key }}" {{ request('specialty') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">{{ __('Note Type') }}</label>
                <select name="note_type" class="form-control">
                    <option value="">{{ __('All') }}</option>
                    @foreach(\App\Models\ClinicalNote::NOTE_TYPES as $key => $label)
                        <option value="{{ $key }}" {{ request('note_type') == $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">{{ __('Status') }}</label>
                <select name="status" class="form-control">
                    <option value="">{{ __('All') }}</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>{{ __('Draft') }}</option>
                    <option value="signed" {{ request('status') == 'signed' ? 'selected' : '' }}>{{ __('Signed') }}</option>
                </select>
            </div>
            <div class="col-md-3 d-flex align-items-end">
                <button type="submit" class="btn btn-primary mr-2"><i class="las la-filter"></i> {{ __('Filter') }}</button>
                <a href="{{ route('clinic.clinical-notes.index') }}" class="btn btn-outline-secondary">{{ __('Clear') }}</a>
            </div>
        </form>
    </div>
</div>

<!-- Notes Table -->
<div class="card border-0 shadow-sm" style="border-radius: 15px;">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h5 class="font-weight-bold mb-0">{{ __('Clinical Notes') }}</h5>
        <a href="{{ route('clinic.clinical-notes.create') }}" class="btn btn-primary">
            <i class="las la-plus"></i> {{ __('New Note') }}
        </a>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Patient') }}</th>
                        <th>{{ __('Type') }}</th>
                        <th>{{ __('Specialty') }}</th>
                        <th>{{ __('Therapist') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Coding') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($notes ?? [] as $note)
                    <tr>
                        <td>{{ $note->created_at->format('M d, Y') }}</td>
                        <td>
                            <a href="{{ route('clinic.patients.show', $note->patient_id) }}" class="text-primary">
                                {{ $note->patient->first_name ?? '' }} {{ $note->patient->last_name ?? '' }}
                            </a>
                        </td>
                        <td>
                            <span class="badge badge-info">{{ \App\Models\ClinicalNote::NOTE_TYPES[$note->note_type] ?? $note->note_type }}</span>
                        </td>
                        <td>{{ \App\Models\ClinicalNote::SPECIALTIES[$note->specialty] ?? $note->specialty }}</td>
                        <td>{{ $note->therapist->name ?? 'N/A' }}</td>
                        <td>
                            @if($note->status === 'signed')
                                <span class="badge badge-success"><i class="las la-check-circle"></i> {{ __('Signed') }}</span>
                            @else
                                <span class="badge badge-warning">{{ __('Draft') }}</span>
                            @endif
                        </td>
                        <td>
                            @if($note->coding_validated)
                                <span class="badge badge-success"><i class="las la-check"></i></span>
                            @elseif($note->coding_errors)
                                <span class="badge badge-danger" title="{{ $note->coding_errors }}"><i class="las la-exclamation-triangle"></i></span>
                            @else
                                <span class="badge badge-secondary">-</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('clinic.clinical-notes.show', $note->id) }}" class="btn btn-sm btn-outline-primary" title="{{ __('View') }}">
                                <i class="las la-eye"></i>
                            </a>
                            @if($note->status !== 'signed')
                                <a href="{{ route('clinic.clinical-notes.edit', $note->id) }}" class="btn btn-sm btn-outline-secondary" title="{{ __('Edit') }}">
                                    <i class="las la-edit"></i>
                                </a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="las la-file-medical fa-3x text-muted mb-3"></i>
                            <p class="text-muted">{{ __('No clinical notes found') }}</p>
                            <a href="{{ route('clinic.clinical-notes.create') }}" class="btn btn-primary">
                                <i class="las la-plus"></i> {{ __('Create First Note') }}
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if(isset($notes) && $notes->hasPages())
        <div class="card-footer bg-white border-0">
            {{ $notes->links() }}
        </div>
        @endif
    </div>
</div>
@endif
@endsection

