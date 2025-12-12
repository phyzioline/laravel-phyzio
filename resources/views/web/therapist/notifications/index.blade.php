@extends('therapist.layouts.app')

@section('content')
<div class="container-fluid py-4" style="background-color: #f8f9fa;">
    <div class="d-flex justify-content-between align-items-center mb-4">
         <h2 class="font-weight-bold text-dark mb-0">{{ __('Notifications') }}</h2>
         <button class="btn btn-sm btn-link">{{ __('Mark all as read') }}</button>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow border-0">
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        @forelse($notifications as $notify)
                         <div class="list-group-item d-flex align-items-start py-3 {{ $notify->unread ? 'bg-light' : '' }}">
                            <div class="icon-circle mr-3 rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px; background-color: {{ $notify->type == 'appointment' ? '#e0f2f1' : '#e3f2fd' }}; color: {{ $notify->type == 'appointment' ? '#00897b' : '#1e88e5' }};">
                                <i class="las {{ $notify->type == 'appointment' ? 'la-calendar-check' : 'la-info-circle' }}" style="font-size: 20px;"></i>
                            </div>
                            <div class="flex-grow-1">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-1 font-weight-bold text-dark">{{ $notify->title }}</h6>
                                    <small class="text-muted">{{ $notify->time }}</small>
                                </div>
                                <p class="mb-0 text-muted small">{{ $notify->message }}</p>
                            </div>
                            @if($notify->unread)
                                <div class="ml-2">
                                    <span class="badge badge-dot bg-primary" style="width: 10px; height: 10px; border-radius: 50%; display:inline-block;"></span>
                                </div>
                            @endif
                         </div>
                        @empty
                         <div class="text-center py-5">
                            <i class="las la-bell-slash text-muted mb-2" style="font-size: 40px;"></i>
                            <p class="text-muted">{{ __('No notifications.') }}</p>
                         </div>
                        @endforelse
                    </div>
                </div>
                 <div class="card-footer bg-white text-center py-3">
                    <button class="btn btn-light btn-sm">{{ __('Load More') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
