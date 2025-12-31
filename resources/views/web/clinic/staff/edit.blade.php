@extends('web.layouts.dashboard_master')

@section('title', 'Edit Staff')
@section('header_title', 'Edit Staff Member')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0" style="border-radius: 15px;">
             <div class="card-header bg-white border-0 py-3">
                <h5 class="font-weight-bold mb-0">Staff Information</h5>
            </div>
            <div class="card-body">
                @if(!$clinic)
                    <div class="alert alert-warning mb-4">
                        <i class="las la-exclamation-triangle"></i> 
                        <strong>Warning:</strong> No clinic found. You need to have a clinic associated with your account to edit staff members.
                    </div>
                @endif
                
                @php
                    $nameParts = explode(' ', $staffMember->name ?? '', 2);
                    $firstName = old('first_name', $staffMember->first_name ?? ($nameParts[0] ?? ''));
                    $lastName = old('last_name', $staffMember->last_name ?? ($nameParts[1] ?? ''));
                @endphp
                <form action="{{ route('clinic.staff.update', $staffMember->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="first_name">First Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('first_name') is-invalid @enderror" 
                                   id="first_name" name="first_name" 
                                   value="{{ $firstName }}" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="last_name">Last Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('last_name') is-invalid @enderror" 
                                   id="last_name" name="last_name" 
                                   value="{{ $lastName }}" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $staffMember->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group col-md-6">
                            <label for="phone">Phone <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" name="phone" value="{{ old('phone', $staffMember->phone) }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="role">Role <span class="text-danger">*</span></label>
                        <select class="form-control @error('role') is-invalid @enderror" id="role" name="role" required>
                            <option value="">Select Role</option>
                            <option value="staff" {{ old('role', $staffMember->type) == 'staff' ? 'selected' : '' }}>Staff</option>
                            <option value="receptionist" {{ old('role', $staffMember->type) == 'receptionist' ? 'selected' : '' }}>Receptionist</option>
                            <option value="nurse" {{ old('role', $staffMember->type) == 'nurse' ? 'selected' : '' }}>Nurse</option>
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="text-right mt-4">
                        <a href="{{ route('clinic.staff.index') }}" class="btn btn-light mr-2">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">Update Staff Member</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

