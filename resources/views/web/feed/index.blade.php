@extends('web.layouts.app')

@section('content')
<div class="feed-wrapper" style="padding-top: 190px;">
    <div class="container-fluid px-0">
        <div class="row g-0 justify-content-center">
            <div class="col-12 col-lg-8 col-xl-6">
                {{-- Mobile App Container --}}
                <div class="feed-container bg-white">
                    
                    {{-- Category Filter Buttons (Horizontal Scroll) --}}
                    <div class="category-scroll-wrapper bg-light py-3 sticky-top" style="top: 160px; z-index: 100;">
                        <div class="container">
                            <div class="category-pills d-flex gap-3 overflow-auto no-scrollbar pb-2">
                                <a href="{{ route('feed.index.' . app()->getLocale()) }}" 
                                   class="category-pill {{ !request('type') ? 'active' : '' }}">
                                    <div class="pill-icon">
                                        <i class="bi bi-grid-3x3-gap"></i>
                                    </div>
                                    <span>{{ __('All') }}</span>
                                </a>
                                <a href="{{ route('feed.index.' . app()->getLocale(), ['type' => 'product']) }}" 
                                   class="category-pill {{ request('type') == 'product' ? 'active' : '' }}">
                                    <div class="pill-icon">
                                        <i class="bi bi-cart3"></i>
                                    </div>
                                    <span>{{ __('Products') }}</span>
                                </a>
                                <a href="{{ route('feed.index.' . app()->getLocale(), ['type' => 'job']) }}" 
                                   class="category-pill {{ request('type') == 'job' ? 'active' : '' }}">
                                    <div class="pill-icon">
                                        <i class="bi bi-briefcase"></i>
                                    </div>
                                    <span>{{ __('Jobs') }}</span>
                                </a>
                                <a href="{{ route('feed.index.' . app()->getLocale(), ['type' => 'course']) }}" 
                                   class="category-pill {{ request('type') == 'course' ? 'active' : '' }}">
                                    <div class="pill-icon">
                                        <i class="bi bi-book"></i>
                                    </div>
                                    <span>{{ __('Courses') }}</span>
                                </a>
                                <a href="{{ route('feed.index.' . app()->getLocale(), ['type' => 'therapist']) }}" 
                                   class="category-pill {{ request('type') == 'therapist' ? 'active' : '' }}">
                                    <div class="pill-icon">
                                        <i class="bi bi-heart-pulse"></i>
                                    </div>
                                    <span>{{ __('Therapists') }}</span>
                                </a>
                                <a href="{{ route('feed.index.' . app()->getLocale(), ['type' => 'my_posts']) }}" 
                                   class="category-pill {{ request('type') == 'my_posts' ? 'active' : '' }}">
                                    <div class="pill-icon">
                                        <i class="bi bi-person-circle"></i>
                                    </div>
                                    <span>{{ __('My Posts') }}</span>
                                </a>
                            </div>
                        </div>
                    </div>

                    {{-- Feed Content --}}
                    <div class="feed-content py-3" style="margin-bottom: 120px;">
                        <div class="container">
                            
                            {{-- Create Post Box --}}
                            <div class="card border-0 shadow-sm mb-3 rounded-3">
                                <div class="card-body p-3">
                                    <form action="{{ route('feed.store.' . app()->getLocale()) }}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <div class="d-flex gap-3 mb-3">
                                            <div class="user-avatar-sm">
                                                {{ substr(auth()->user()->name, 0, 1) }}
                                            </div>
                                            <textarea name="description" class="form-control border-0 bg-light" rows="2" 
                                                      placeholder="{{ __('Share something with the community...') }}" required></textarea>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <label for="media_upload" class="btn btn-sm btn-light rounded-pill">
                                                <i class="bi bi-image text-teal"></i> {{ __('Photo') }}
                                            </label>
                                            <input type="file" id="media_upload" name="media" class="d-none" accept="image/*">
                                            <button type="submit" class="btn btn-teal btn-sm rounded-pill px-4">
                                                {{ __('Post') }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            {{-- Feed Items --}}
                            @forelse($feedItems as $item)
                                <div class="feed-card card border-0 shadow-sm mb-3 rounded-3 animate-fade-in">
                                    <div class="card-body p-3">
                                        {{-- Author Header --}}
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="user-avatar me-3">
                                                {{ substr($item->sourceable ? $item->sourceable->name : 'Phyzioline', 0, 1) }}
                                            </div>
                                            <div class="flex-grow-1">
                                                <div class="d-flex align-items-center gap-2">
                                                    <h6 class="mb-0 fw-semibold">
                                                        {{ $item->sourceable ? $item->sourceable->name : 'Phyzioline System' }}
                                                    </h6>
                                                    <i class="bi bi-patch-check-fill text-teal" style="font-size: 0.9rem;"></i>
                                                </div>
                                                <small class="text-muted">{{ $item->created_at->diffForHumans() }}</small>
                                            </div>
                                            <span class="badge bg-teal-light text-teal rounded-pill px-3">
                                                {{ __(ucfirst($item->type)) }}
                                            </span>
                                        </div>

                                        {{-- Content --}}
                                        <div class="post-content mb-3">
                                            @if($item->title)
                                                <h6 class="fw-bold mb-2">{{ $item->title }}</h6>
                                            @endif
                                            <p class="mb-2" style="line-height: 1.6;">{{ $item->description }}</p>
                                        </div>

                                        {{-- Type-Specific Content Cards --}}
                                        @if($item->type == 'product' && $item->sourceable)
                                            <div class="product-card bg-light rounded-3 p-3 mb-3">
                                                <div class="row g-3 align-items-center">
                                                    <div class="col-4">
                                                        @if($item->media_url)
                                                            <img src="{{ $item->media_url }}" class="img-fluid rounded-3" alt="Product">
                                                        @else
                                                            <div class="placeholder-img bg-white rounded-3 d-flex align-items-center justify-content-center" style="height: 100px;">
                                                                <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-8">
                                                        <h6 class="fw-bold mb-2">{{ $item->sourceable->product_name_en ?? $item->sourceable->product_name_ar }}</h6>
                                                        <p class="text-teal fw-bold mb-0" style="font-size: 1.1rem;">
                                                            {{ __('EGP') }} {{ number_format($item->sourceable->product_price, 0) }}
                                                        </p>
                                                    </div>
                                                </div>
                                                @if($item->action_link)
                                                    <a href="{{ $item->action_link }}" class="btn btn-teal w-100 rounded-pill mt-3">
                                                        {{ __('Buy Now') }}
                                                    </a>
                                                @endif
                                            </div>
                                        @elseif($item->type == 'job' && $item->sourceable)
                                            <div class="job-card bg-light rounded-3 p-3 mb-3">
                                                <h6 class="fw-bold text-teal mb-2">{{ $item->sourceable->job_title }}</h6>
                                                <div class="d-flex flex-column gap-2">
                                                    <small><i class="bi bi-geo-alt text-teal"></i> {{ $item->sourceable->job_location }}</small>
                                                    @if($item->sourceable->salary_range)
                                                        <small><i class="bi bi-cash text-teal"></i> {{ $item->sourceable->salary_range }}</small>
                                                    @endif
                                                </div>
                                                @if($item->action_link)
                                                    <a href="{{ $item->action_link }}" class="btn btn-teal w-100 rounded-pill mt-3">
                                                        {{ __('Apply Now') }}
                                                    </a>
                                                @endif
                                            </div>
                                        @elseif($item->type == 'course' && $item->sourceable)
                                            <div class="course-card bg-light rounded-3 p-3 mb-3">
                                                <h6 class="fw-bold text-teal mb-2">{{ $item->sourceable->course_name_en ?? $item->sourceable->course_name_ar }}</h6>
                                                @if($item->sourceable->duration)
                                                    <small class="text-muted"><i class="bi bi-clock"></i> {{ $item->sourceable->duration }}</small>
                                                @endif
                                                @if($item->action_link)
                                                    <a href="{{ $item->action_link }}" class="btn btn-teal w-100 rounded-pill mt-3">
                                                        {{ __('Enroll Now') }}
                                                    </a>
                                                @endif
                                            </div>
                                        @elseif($item->media_url)
                                            {{-- Regular media post --}}
                                            <img src="{{ $item->media_url }}" class="img-fluid rounded-3 mb-3" alt="Post media">
                                        @endif

                                        {{-- Action Bar --}}
                                        <div class="action-bar d-flex justify-content-between align-items-center pt-3 border-top">
                                            <div class="d-flex gap-4">
                                                <form action="{{ route('feed.like.' . app()->getLocale(), $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-link text-decoration-none p-0 {{ $item->liked_by_user ? 'text-danger' : 'text-muted' }}">
                                                        <i class="bi {{ $item->liked_by_user ? 'bi-heart-fill' : 'bi-heart' }}"></i>
                                                        <span class="ms-1">{{ $item->likes_count ?? 0 }}</span>
                                                    </button>
                                                </form>
                                                <button class="btn btn-sm btn-link text-muted text-decoration-none p-0">
                                                    <i class="bi bi-chat"></i>
                                                    <span class="ms-1">{{ $item->comments_count ?? 0 }}</span>
                                                </button>
                                                <button class="btn btn-sm btn-link text-muted text-decoration-none p-0">
                                                    <i class="bi bi-share"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-5">
                                    <div class="empty-state-icon mb-3">
                                        <i class="bi bi-inbox" style="font-size: 4rem; color: #02767F;"></i>
                                    </div>
                                    <h5 class="text-muted">{{ __('No posts yet') }}</h5>
                                    <p class="text-muted">{{ __('Be the first to share something!') }}</p>
                                </div>
                            @endforelse

                            {{-- Pagination --}}
                            @if($feedItems->hasPages())
                                <div class="mt-4">
                                    {{ $feedItems->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Teal Theme Colors */
:root {
    --teal-primary: #02767F;
    --teal-dark: #015a62;
    --teal-light: rgba(2, 118, 127, 0.1);
}

/* Mobile App Container */
.feed-container {
    min-height: 100vh;
    background: #f8f9fa;
}

/* Category Pills (Horizontal Scroll) */
.category-pills {
    overflow-x: auto;
    white-space: nowrap;
    -webkit-overflow-scrolling: touch;
}

.category-pill {
    display: inline-flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    text-decoration: none;
    transition: all 0.3s ease;
}

.pill-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: var(--teal-primary);
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.category-pill span {
    font-size: 0.75rem;
    color: #6c757d;
    font-weight: 500;
}

.category-pill.active .pill-icon {
    background: var(--teal-primary);
    color: white;
    border-color: var(--teal-primary);
    transform: scale(1.05);
}

.category-pill.active span {
    color: var(--teal-primary);
    font-weight: 600;
}

.category-pill:hover .pill-icon {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(2, 118, 127, 0.2);
}

/* Hide Scrollbar */
.no-scrollbar::-webkit-scrollbar {
    display: none;
}
.no-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}

/* User Avatars */
.user-avatar, .user-avatar-sm {
    border-radius: 50%;
    background: linear-gradient(135deg, var(--teal-primary), #04a5b8);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    text-transform: uppercase;
}

.user-avatar {
    width: 48px;
    height: 48px;
    font-size: 1.2rem;
}

.user-avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 1rem;
    flex-shrink: 0;
}

/* Feed Cards */
.feed-card {
    transition: all 0.3s ease;
}

.feed-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.1) !important;
}

/* Teal Button */
.btn-teal {
    background: var(--teal-primary);
    color: white;
    border: none;
    font-weight: 500;
}

.btn-teal:hover {
    background: var(--teal-dark);
    color: white;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(2, 118, 127, 0.3);
}

/* Teal Badge */
.bg-teal-light {
    background-color: var(--teal-light) !important;
}

.text-teal {
    color: var(--teal-primary) !important;
}

.badge.bg-teal-light {
    font-weight: 500;
    font-size: 0.7rem;
}

/* Animations */
@keyframes fade-in {
    from {
        opacity: 0;
        transform: translateY(15px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.animate-fade-in {
    animation: fade-in 0.4s ease-out forwards;
}

/* Product/Job/Course Cards */
.product-card, .job-card, .course-card {
    transition: all 0.3s ease;
}

/* Action Bar Icons */
.action-bar button {
    font-size: 0.95rem;
}

.action-bar i {
    font-size: 1.2rem;
}

/* RTL Support */
[dir="rtl"] .category-pills {
    direction: rtl;
}

[dir="rtl"] .feed-card {
    text-align: right;
}

/* Responsive */
@media (max-width: 768px) {
    .feed-wrapper {
        padding-top: 160px !important;
    }
    
    .category-scroll-wrapper {
        top: 140px !important;
    }
}
</style>
@endsection
