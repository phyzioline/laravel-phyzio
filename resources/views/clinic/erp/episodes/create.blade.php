@extends('web.layouts.dashboard_master')

@section('title', 'New Episode of Care')
@section('header_title', 'New Patient Intake')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <form action="{{ route('clinic.episodes.store') }}" method="POST">
                    @csrf
                    
                    <h5 class="text-primary mb-3">Patient Selection</h5>
                    <div class="form-group">
                        <label>Select Patient</label>
                        <select name="patient_id" class="form-control" required>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}">{{ $patient->name }} ({{ $patient->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <h5 class="text-primary mt-4 mb-3">Clinical Context</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Specialty (Determines Forms)</label>
                                <select name="specialty" class="form-control" required>
                                    <option value="orthopedic">Orthopedic</option>
                                    <option value="neurological">Neurological</option>
                                    <option value="pediatric">Pediatric</option>
                                    <option value="sports">Sports</option>
                                    <option value="geriatric">Geriatric</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Primary Therapist</label>
                                <select name="primary_therapist_id" class="form-control" required>
                                    @foreach($therapists as $therapist)
                                        <option value="{{ $therapist->id }}">{{ $therapist->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Chief Complaint</label>
                        <textarea name="chief_complaint" class="form-control" rows="3" required placeholder="Describe the main issue..."></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Start Date</label>
                                <input type="date" name="start_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>ICD-10 Diagnosis (Optional)</label>
                                <input type="text" name="diagnosis_icd" class="form-control" placeholder="e.g. M23.5">
                            </div>
                        </div>
                    </div>

                    <div class="text-right mt-4">
                        <button type="submit" class="btn btn-primary btn-lg">Create Episode & Open Chart</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
