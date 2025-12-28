@extends('web.layouts.dashboard_master')

@section('title', 'Add Department')
@section('header_title', 'Add New Department')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0" style="border-radius: 15px;">
             <div class="card-header bg-white border-0 py-3">
                <h5 class="font-weight-bold mb-0">Department Details</h5>
            </div>
            <div class="card-body">
                @if($errors->any())
                    <div class="alert alert-danger mb-4">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger mb-4">
                        {{ session('error') }}
                    </div>
                @endif
                
                @if(session('success'))
                    <div class="alert alert-success mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if(!$clinic)
                    <div class="alert alert-warning">
                        <i class="las la-exclamation-triangle"></i> Please set up your clinic first before adding departments.
                    </div>
                @endif
                
                <form method="POST" action="{{ route('clinic.departments.store') }}">
                    @csrf
                    <div class="form-group">
                        <label>Physical Therapy Specialty <span class="text-danger">*</span></label>
                        <select name="specialty" class="form-control @error('specialty') is-invalid @enderror" required {{ !$clinic ? 'disabled' : '' }}>
                            <option value="">Select Specialty</option>
                            @foreach(\App\Models\ClinicSpecialty::getAvailableSpecialties() as $key => $name)
                                <option value="{{ $key }}" {{ old('specialty') == $key ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('specialty')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Select the physical therapy specialty for this department. This will activate specialty-specific features.</small>
                    </div>

                    <div class="alert alert-info">
                        <i class="las la-info-circle"></i> <strong>Note:</strong> Adding a specialty department will activate specialty-specific features, assessment forms, and treatment templates for your clinic.
                    </div>

                    <div class="text-right mt-4">
                        <a href="{{ route('clinic.departments.index') }}" class="btn btn-light mr-2">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4" {{ !$clinic ? 'disabled' : '' }}>
                            <i class="las la-plus"></i> Add Department
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
