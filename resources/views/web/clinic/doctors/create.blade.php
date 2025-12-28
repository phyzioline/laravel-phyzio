@extends('web.layouts.dashboard_master')

@section('title', 'Add Doctor')
@section('header_title', 'Register New Doctor')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0" style="border-radius: 15px;">
             <div class="card-header bg-white border-0 py-3">
                <h5 class="font-weight-bold mb-0">Doctor Information</h5>
            </div>
            <div class="card-body">
                @if(!$clinic)
                    <div class="alert alert-warning mb-4">
                        <i class="las la-exclamation-triangle"></i> 
                        <strong>Warning:</strong> No clinic found. You need to have a clinic associated with your account to register doctors.
                    </div>
                @endif
                
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
                
                <form method="POST" action="{{ route('clinic.doctors.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>First Name <span class="text-danger">*</span></label>
                            <input type="text" name="first_name" class="form-control @error('first_name') is-invalid @enderror" placeholder="Dr. John" value="{{ old('first_name') }}" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label>Last Name <span class="text-danger">*</span></label>
                            <input type="text" name="last_name" class="form-control @error('last_name') is-invalid @enderror" placeholder="Doe" value="{{ old('last_name') }}" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Specialization <span class="text-danger">*</span></label>
                        <select name="specialization" class="form-control @error('specialization') is-invalid @enderror" required>
                            <option value="">Select Specialization</option>
                            <option value="Orthopedic" {{ old('specialization') == 'Orthopedic' ? 'selected' : '' }}>Orthopedic</option>
                            <option value="Sports Physiotherapy" {{ old('specialization') == 'Sports Physiotherapy' ? 'selected' : '' }}>Sports Physiotherapy</option>
                            <option value="Neurology" {{ old('specialization') == 'Neurology' ? 'selected' : '' }}>Neurology</option>
                            <option value="Pediatrics" {{ old('specialization') == 'Pediatrics' ? 'selected' : '' }}>Pediatrics</option>
                            <option value="Geriatric" {{ old('specialization') == 'Geriatric' ? 'selected' : '' }}>Geriatric</option>
                            <option value="Women's Health" {{ old('specialization') == 'Women\'s Health' ? 'selected' : '' }}>Women's Health</option>
                            <option value="Cardiorespiratory" {{ old('specialization') == 'Cardiorespiratory' ? 'selected' : '' }}>Cardiorespiratory</option>
                        </select>
                        @error('specialization')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Email <span class="text-danger">*</span></label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="email@clinic.com" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label>Phone <span class="text-danger">*</span></label>
                            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="+123456789" value="{{ old('phone') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Professional Bio</label>
                        <textarea name="bio" class="form-control @error('bio') is-invalid @enderror" rows="4" placeholder="Brief professional background...">{{ old('bio') }}</textarea>
                        @error('bio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Password (Optional)</label>
                        <input type="password" name="password" class="form-control" placeholder="Leave blank for default password">
                        <small class="text-muted">Default password will be 'password' if left blank. Doctor should change it on first login.</small>
                    </div>

                    <div class="text-right mt-4">
                        <a href="{{ route('clinic.doctors.index') }}" class="btn btn-light mr-2">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">Create Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
