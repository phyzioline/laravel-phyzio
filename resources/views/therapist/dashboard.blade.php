@extends('therapist.layouts.app')

@section('content')
<div class="row">
    <div class="col-12 col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="mb-0">Welcome back, {{ auth()->user()->name }}</h5>
                <p class="mb-0">Manage your profile, appointments, and availability from here.</p>
            </div>
        </div>
    </div>
</div>
@endsection
