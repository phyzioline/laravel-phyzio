@extends('web.layouts.dashboard_master')

@section('title', 'Select Clinic')
@section('header_title', 'Select Clinic to Schedule')

@section('content')
<div class="row">
    @foreach($clinics ?? [] as $clinic)
    <div class="col-md-4 mb-4">
        <div class="card border-0 shadow-sm h-100" style="border-radius: 15px;">
            <div class="card-body">
                <h5 class="font-weight-bold">{{ $clinic->name }}</h5>
                <p class="text-muted mb-3">{{ $clinic->address ?? 'N/A' }}</p>
                <a href="{{ route('patient.self-schedule.index', ['clinic_id' => $clinic->id]) }}" class="btn btn-primary btn-block">
                    <i class="las la-calendar-check"></i> {{ __('Schedule Appointment') }}
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection

