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
                <form>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>First Name</label>
                            <input type="text" class="form-control" placeholder="Dr. John">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Last Name</label>
                            <input type="text" class="form-control" placeholder="Doe">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Specialization</label>
                        <select class="form-control hover-shadow">
                            <option>Sports Physiotherapy</option>
                            <option>Orthopedics</option>
                            <option>Neurology</option>
                            <option>Pediatrics</option>
                        </select>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Email</label>
                            <input type="email" class="form-control" placeholde="email@clinic.com">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Phone</label>
                            <input type="text" class="form-control" placeholder="+123456789">
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>Professional Bio</label>
                        <textarea class="form-control" rows="4"></textarea>
                    </div>

                    <div class="form-group">
                        <label>Upload Photo</label>
                         <div class="custom-file">
                            <input type="file" class="custom-file-input">
                            <label class="custom-file-label">Choose file...</label>
                        </div>
                    </div>

                    <div class="text-right mt-4">
                        <button type="button" class="btn btn-light mr-2">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4">Create Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
