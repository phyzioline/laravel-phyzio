@extends('web.layouts.dashboard_master')

@section('title', 'Notifications')
@section('header_title', 'Alerts & Notifications')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    @foreach($notifications as $notify)
                    <li class="list-group-item py-3">
                        <div class="d-flex w-100 justify-content-between align-items-center mb-1">
                            <h6 class="mb-0 font-weight-bold text-{{ $notify->type == 'warning' ? 'warning' : 'info' }}">
                                {{ $notify->title }}
                            </h6>
                            <small class="text-muted">{{ $notify->time }}</small>
                        </div>
                        <p class="mb-0 text-muted small">{{ $notify->message }}</p>
                    </li>
                    @endforeach
                </ul>
            </div>
             <div class="card-footer bg-white text-center">
                <button class="btn btn-sm btn-link">Load More</button>
            </div>
        </div>
    </div>
</div>
@endsection
