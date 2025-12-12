@extends('web.layouts.dashboard_master')

@section('title', 'Add Staff')
@section('header_title', 'Register New Staff')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0" style="border-radius: 15px;">
             <div class="card-header bg-white border-0 py-3">
                <h5 class="font-weight-bold mb-0">Staff Information</h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Full Name</label>
                            <input type="text" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Role</label>
                            <select class="form-control">
                                <option>Receptionist</option>
                                <option>Nurse</option>
                                <option>Administrator</option>
                                <option>Cleaner</option>
                            </select>
                        </div>
                    </div>
                    
                     <div class="form-row">
                        <div class="form-group col-md-6">
                            <label>Email</label>
                            <input type="email" class="form-control">
                        </div>
                        <div class="form-group col-md-6">
                            <label>Phone</label>
                            <input type="text" class="form-control">
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Address</label>
                        <input type="text" class="form-control">
                    </div>

                     <div class="form-group">
                        <label>Status</label>
                        <select class="form-control">
                            <option>Active</option>
                            <option>On Leave</option>
                        </select>
                    </div>

                    <div class="text-right mt-4">
                        <button type="button" class="btn btn-light mr-2">Cancel</button>
                        <button type="submit" class="btn btn-primary px-4">Save Staff Member</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
