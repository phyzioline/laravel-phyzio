@extends('web.therapist.layout')

@section('header_title', 'Edit Profile')

@section('content')
<div class="card-box">
    <form action="{{ route('therapist.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Full Name</label>
                <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
            </div>
            <div class="col-md-6 mb-3">
                <label>Phone Number</label>
                <input type="text" name="phone" class="form-control" value="{{ $user->phone }}" required>
            </div>
            <div class="col-md-12 mb-3">
                <label>Bio (About You)</label>
                <textarea name="bio" class="form-control" rows="4">{{ $user->bio ?? '' }}</textarea>
            </div>
            <!-- Add more fields like Experience, Specialization here -->
        </div>

        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>
@endsection
