@extends('therapist.layouts.app')

@section('content')
<div class="row">
    <div class="col-12 col-xl-8 mx-auto">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="mb-3">Edit Profile</h5>
                <form action="{{ route('therapist.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Specialization</label>
                        <input type="text" name="specialization" class="form-control" value="{{ old('specialization', $profile->specialization ?? '') }}">
                    </div>

                     <div class="mb-3">
                        <label class="form-label">Bio</label>
                        <textarea name="bio" class="form-control" rows="4">{{ old('bio', $profile->bio ?? '') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Home Visit Rate (EGP)</label>
                        <input type="number" name="home_visit_rate" class="form-control" value="{{ old('home_visit_rate', $profile->home_visit_rate ?? '') }}">
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
