@extends('web.layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8" style="max-width: 1200px;">
    <div class="row">
        {{-- Left Sidebar - Filters --}}
        <div class="col-md-3 d-none d-md-block">
            <div class="card mb-4 shadow-sm sticky-top" style="top: 170px;">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">{{ __('Filters') }}</h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('feed.index.' . app()->getLocale()) }}" 
                           class="list-group-item list-group-item-action {{ !request('type') ? 'active-teal' : '' }}">
                            <i class="bi bi-grid"></i> {{ __('All Posts') }}
                        </a>
                        <a href="{{ route('feed.index.' . app()->getLocale(), ['type' => 'product']) }}" 
                           class="list-group-item list-group-item-action {{ request('type') == 'product' ? 'active-teal' : '' }}">
                            <i class="bi bi-cart3"></i> {{ __('Products') }}
                        </a>
                        <a href="{{ route('feed.index.' . app()->getLocale(), ['type' => 'job']) }}" 
                           class="list-group-item list-group-item-action {{ request('type') == 'job' ? 'active-teal' : '' }}">
                            <i class="bi bi-briefcase"></i> {{ __('Jobs') }}
                        </a>
                        <a href="{{ route('feed.index.' . app()->getLocale(), ['type' => 'course']) }}" 
                           class="list-group-item list-group-item-action {{ request('type') == 'course' ? 'active-teal' : '' }}">
                            <i class="bi bi-book"></i> {{ __('Courses') }}
                        </a>
                        <a href="{{ route('feed.index.' . app()->getLocale(), ['type' => 'therapist']) }}" 
                           class="list-group-item list-group-item-action {{ request('type') == 'therapist' ? 'active-teal' : '' }}">
                            <i class="bi bi-heart-pulse"></i> {{ __('Therapists') }}
                        </a>
                        <a href="{{ route('feed.index.' . app()->getLocale(), ['type' => 'my_posts']) }}" 
                           class="list-group-item list-group-item-action {{ request('type') == 'my_posts' ? 'active-teal' : '' }}">
                            <i class="bi bi-person-circle"></i> {{ __('My Posts') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Main Feed --}}
        <div class="col-md-6">
            {{-- Create Post Box --}}
            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <form action="{{ route('feed.store.' . app()->getLocale()) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <textarea name="description" class="form-control" rows="3" placeholder="{{ __('Share something with the community...') }}" required></textarea>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <label for="media_upload" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-image"></i> {{ __('Photo') }}
                                </label>
                                <input type="file" id="media_upload" name="media" class="d-none" accept="image/*">
                            </div>
                            <button type="submit" class="btn btn-teal">{{ __('Post') }}</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Feed Items --}}
            @forelse($feedItems as $item)
                <div class="card mb-4 shadow-sm animate-fade-in">
                    <div class="card-body">
                        {{-- Author Info --}}
                        <div class="d-flex align-items-center mb-3">
                            <div class="avatar-circle me-3">
                                {{ substr($item->sourceable ? $item->sourceable->name : 'System', 0, 1) }}
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-semibold">{{ $item->sourceable ? $item->sourceable->name : 'Phyzioline System' }}</h6>
                                <small class="text-muted">{{ $item->created_at->diffForHumans() }}</small>
                            </div>
                            <span class="badge badge-pill" style="background-color: #02767F; color: white;">
                                {{ __(ucfirst($item->type)) }}
                            </span>
                        </div>

                        {{-- Content --}}
                        <div class="mb-3">
                            @if($item->title)
                                <h5 class="card-title">{{ $item->title }}</h5>
                            @endif
                            <p class="card-text">{{ $item->description }}</p>
                        </div>

                        {{-- Media --}}
                        @if($item->media_url)
                            <img src="{{ $item->media_url }}" class="img-fluid rounded mb-3" alt="Post media">
                        @endif

                        {{-- Type-specific Content --}}
                        @if($item->type == 'product' && $item->sourceable)
                            <div class="bg-light p-3 rounded mb-3">
                                <h6 class="text-teal">{{ $item->sourceable->product_name_en ?? $item->sourceable->product_name_ar }}</h6>
                                <p class="mb-2">{{ __('Price') }}: <strong>{{ $item->sourceable->product_price }} EGP</strong></p>
                            </div>
                        @elseif($item->type == 'job' && $item->sourceable)
                            <div class="bg-light p-3 rounded mb-3">
                                <h6 class="text-teal">{{ $item->sourceable->job_title }}</h6>
                                <p class="mb-1"><i class="bi bi-geo-alt"></i> {{ $item->sourceable->job_location }}</p>
                                @if($item->sourceable->salary_range)
                                    <p class="mb-0"><i class="bi bi-cash"></i> {{ $item->sourceable->salary_range }}</p>
                                @endif
                            </div>
                        @elseif($item->type == 'course' && $item->sourceable)
                            <div class="bg-light p-3 rounded mb-3">
                                <h6 class="text-teal">{{ $item->sourceable->course_name_en ?? $item->sourceable->course_name_ar }}</h6>
                                @if($item->sourceable->duration)
                                    <p class="mb-0"><i class="bi bi-clock"></i> {{ $item->sourceable->duration }}</p>
                                @endif
                            </div>
                        @endif

                        {{-- Actions --}}
                        <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                            <div>
                                <form action="{{ route('feed.like.' . app()->getLocale(), $item->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-link text-decoration-none {{ $item->liked_by_user ? 'text-danger' : 'text-muted' }}">
                                        <i class="bi {{ $item->liked_by_user ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                        {{ $item->likes_count ?? 0 }}
                                    </button>
                                </form>
                                <button class="btn btn-sm btn-link text-muted text-decoration-none">
                                    <i class="bi bi-chat"></i> {{ $item->comments_count ?? 0 }}
                                </button>
                            </div>
                            @if($item->action_link)
                                <a href="{{ $item->action_link }}" class="btn btn-sm btn-teal">
                                    {{ $item->action_text ?? __('View Details') }}
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="card shadow-sm">
                    <div class="card-body text-center py-5">
                        <i class="bi bi-inbox" style="font-size: 3rem; color: #02767F;"></i>
                        <h5 class="mt-3">{{ __('No posts yet') }}</h5>
                        <p class="text-muted">{{ __('Be the first to share something!') }}</p>
                    </div>
                </div>
            @endforelse

            {{-- Pagination --}}
            <div class="mt-4">
                {{ $feedItems->links() }}
            </div>
        </div>

        {{-- Right Sidebar - Suggestions --}}
        <div class="col-md-3 d-none d-md-block">
            <div class="card mb-4 shadow-sm sticky-top" style="top: 170px;">
                <div class="card-header bg-white border-bottom">
                    <h5 class="mb-0">{{ __('Quick Links') }}</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        <a href="{{ route('web.shop.show.' . app()->getLocale()) }}" class="list-group-item list-group-item-action border-0 px-0">
                            <i class="bi bi-shop text-teal"></i> {{ __('Browse Shop') }}
                        </a>
                        <a href="{{ route('web.jobs.index.' . app()->getLocale()) }}" class="list-group-item list-group-item-action border-0 px-0">
                            <i class="bi bi-briefcase text-teal"></i> {{ __('Find Jobs') }}
                        </a>
                        <a href="{{ route('web.home_visits.index.' . app()->getLocale()) }}" class="list-group-item list-group-item-action border-0 px-0">
                            <i class="bi bi-heart-pulse text-teal"></i> {{ __('Book Home Visit') }}
                        </a>
                        <a href="{{ route('web.courses.index') }}" class="list-group-item list-group-item-action border-0 px-0">
                            <i class="bi bi-book text-teal"></i> {{ __('Explore Courses') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.btn-teal {
    background-color: #02767F;
    color: white;
    border: none;
}
.btn-teal:hover {
    background-color: #015a62;
    color: white;
}
.text-teal {
    color: #02767F !important;
}
.active-teal {
    background-color: #02767F !important;
    color: white !important;
}
.avatar-circle {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(135deg, #02767F, #04a5b8);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: bold;
    font-size: 1.2rem;
}
@keyframes fade-in {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}
.animate-fade-in {
    animation: fade-in 0.3s ease-out forwards;
}
</style>
@endsection
