@extends('dashboard.layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Therapist Profile: {{ $profile->user->name }}</h1>
        <a href="{{ route('dashboard.therapist_profiles.index') }}" class="d-none d-sm-inline-block btn btn-sm btn-secondary shadow-sm">Back to List</a>
    </div>

    <div class="row">
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Overview</h6>
                </div>
                <div class="card-body text-center">
                    @if($profile->profile_image)
                        <img src="{{ asset('storage/'.$profile->profile_image) }}" class="rounded-circle img-fluid mb-3" style="width: 150px; height: 150px; object-fit: cover;" alt="">
                    @else
                        <div class="rounded-circle bg-gray-200 d-inline-flex align-items-center justify-content-center mb-3" style="width: 150px; height: 150px;">
                            <i class="fas fa-user fa-3x text-gray-400"></i>
                        </div>
                    @endif
                    <h4>{{ $profile->user->name }}</h4>
                    <p class="text-muted">{{ $profile->specialization }}</p>
                    <span class="badge badge-{{ $profile->status == 'approved' ? 'success' : 'warning' }}">
                        {{ ucfirst($profile->status) }}
                    </span>
                </div>
            </div>
        </div>
        
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Details</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Email:</div>
                        <div class="col-md-8">{{ $profile->user->email }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Phone:</div>
                        <div class="col-md-8">{{ $profile->user->phone ?? 'N/A' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Home Visit Rate:</div>
                        <div class="col-md-8">{{ $profile->home_visit_rate }} EGP</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Bio:</div>
                        <div class="col-md-8">{{ $profile->bio ?? 'No bio provided.' }}</div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-4 font-weight-bold">Available Areas:</div>
                        <div class="col-md-8">
                            @if($profile->available_areas)
                                @foreach($profile->available_areas as $area)
                                    <span class="badge badge-info mr-1">{{ $area }}</span>
                                @endforeach
                            @else
                                N/A
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
