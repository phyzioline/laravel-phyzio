@extends('web.layouts.app')

@section('content')
<style>
    body {
        font-family: 'Tajawal', sans-serif;
        background: #f3f4f6;
    }
    .feed-app {
        max-width: 480px;
        margin: 0 auto;
        background: white;
        min-height: 100vh;
        padding-top: 180px;
        padding-bottom: 80px;
    }
    .story-pill {
        min-width: 70px;
        text-align: center;
    }
    .story-circle {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: linear-gradient(135deg, #f59e0b, #02767F);
        padding: 3px;
        margin: 0 auto 8px;
    }
    .story-circle.active {
        background: linear-gradient(135deg, #f59e0b, #02767F);
    }
    .story-circle.inactive {
        background: #e5e7eb;
    }
    .story-inner {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }
    .feed-card {
        background: white;
        margin-bottom: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    .btn-teal {
        background: #02767F;
        color: white;
        padding: 10px 20px;
        border-radius: 8px;
        font-weight: 600;
        border: none;
        width: 100%;
    }
    .btn-teal:hover {
        background: #015a62;
        color: white;
    }
    .bottom-nav {
        position: fixed;
        bottom: 0;
        left: 0;
        right: 0;
        background: white;
        border-top: 1px solid #e5e7eb;
        padding: 8px 16px;
        display: flex;
        justify-content: space-around;
        z-index: 1000;
        max-width: 480px;
        margin: 0 auto;
    }
    .nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 4px;
        color: #9ca3af;
        text-decoration: none;
        font-size: 12px;
    }
    .nav-item.active {
        color: #02767F;
    }
    .nav-item .icon {
        font-size: 24px;
    }
    .create-btn {
        background: #02767F;
        color: white;
        border-radius: 50%;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: -16px;
        border: 4px solid white;
        box-shadow: 0 2px 8px rgba(2, 118, 127, 0.3);
    }
</style>

<div class="feed-app">
    {{-- Header --}}
    <div class="sticky-top bg-white shadow-sm p-3 d-flex justify-between align-items-center" style="position: fixed; top: 160px; left: 0; right: 0; z-index: 100; max-width: 480px; margin: 0 auto;">
        <div class="d-flex gap-3">
            <div class="position-relative">
                <span style="font-size: 24px;">🔔</span>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 8px;">•</span>
            </div>
            <span style="font-size: 24px;">💬</span>
        </div>
        <div class="d-flex align-items-center gap-2">
            <h5 class="mb-0 fw-bold" style="color: #02767F;">Phyzioline</h5>
            <div class="bg-teal-600 rounded" style="background: #02767F; width: 24px; height: 24px; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 12px;">P</div>
        </div>
    </div>

    {{-- Welcome Banner --}}
    <div class="px-3 py-3 text-center" style="background: #ecfdf5; border-bottom: 1px solid #a7f3d0; margin-top: 60px;">
        <p class="mb-0 small fw-medium" style="color: #047857;">
            👋 {{ __('Welcome to Phyzioline community') }}
        </p>
    </div>

    {{-- Advanced Filters --}}
    @include('web.feed.partials.advanced-filters')

    {{-- Stories / Categories --}}
    <div class="d-flex gap-3 overflow-auto p-3 bg-white" style="overflow-x: auto; white-space: nowrap;">
        <div class="story-pill">
            <div onclick="openCreateModal()" class="story-circle" style="background: #e5e7eb; border: 2px dashed #02767F; cursor: pointer;">
                <div class="story-inner">➕</div>
            </div>
            <small>{{ __('Add') }}</small>
        </div>
        <a href="{{ route('feed.index.' . app()->getLocale()) }}" class="story-pill text-decoration-none">
            <div class="story-circle {{ !request('type') ? 'active' : 'inactive' }}">
                <div class="story-inner">🏠</div>
            </div>
            <small class="text-dark">{{ __('All') }}</small>
        </a>
        <a href="{{ route('feed.index.' . app()->getLocale(), ['type' => 'product']) }}" class="story-pill text-decoration-none">
            <div class="story-circle {{ request('type') == 'product' ? 'active' : 'inactive' }}">
                <div class="story-inner">🛍️</div>
            </div>
            <small class="text-dark">{{ __('Products') }}</small>
        </a>
        <a href="{{ route('feed.index.' . app()->getLocale(), ['type' => 'job']) }}" class="story-pill text-decoration-none">
            <div class="story-circle {{ request('type') == 'job' ? 'active' : 'inactive' }}">
                <div class="story-inner">💼</div>
            </div>
            <small class="text-dark">{{ __('Jobs') }}</small>
        </a>
        <a href="{{ route('feed.index.' . app()->getLocale(), ['type' => 'course']) }}" class="story-pill text-decoration-none">
            <div class="story-circle {{ request('type') == 'course' ? 'active' : 'inactive' }}">
                <div class="story-inner">📚</div>
            </div>
            <small class="text-dark">{{ __('Courses') }}</small>
        </a>
    </div>

    {{-- Feed Posts --}}
    <div class="feed-content">
        @forelse($feedItems as $item)
        <div class="feed-card">
            {{-- Author Header --}}
            <div class="d-flex justify-content-between align-items-center p-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold text-white" style="width: 40px; height: 40px; background: linear-gradient(135deg, #02767F, #04a5b8);">
                        {{ substr($item->sourceable ? $item->sourceable->name : 'P', 0, 1) }}
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-1">
                            <span class="fw-bold small">{{ $item->sourceable ? $item->sourceable->name : 'Phyzioline System' }}</span>
                            <span style="color: #02767F;">☑️</span>
                        </div>
                        <small class="text-muted">{{ $item->created_at->diffForHumans() }}</small>
                    </div>
                </div>
                <span class="badge rounded-pill" style="background: rgba(2, 118, 127, 0.1); color: #02767F; font-size: 10px;">
                    {{ __(ucfirst($item->type)) }}
                </span>
            </div>

            {{-- Content --}}
            <div class="px-3 pb-2">
                <p class="mb-2 small">{{ $item->description }}</p>
            </div>

            {{-- Product Card --}}
            @if($item->type == 'product' && $item->sourceable)
            <div class="mx-3 mb-3 p-3 rounded-3" style="background: #f9fafb; border: 1px solid #e5e7eb;">
                <div class="row g-3 align-items-center">
                    <div class="col-4">
                        @if($item->media_url)
                        <img src="{{ $item->media_url }}" class="img-fluid rounded-3" alt="Product">
                        @else
                        <div class="bg-white rounded-3 d-flex align-items-center justify-center" style="height: 80px;">
                            <span style="font-size: 2rem;">🛍️</span>
                        </div>
                        @endif
                    </div>
                    <div class="col-8">
                        <h6 class="fw-bold mb-2">{{ $item->sourceable->product_name_ar ?? $item->sourceable->product_name_en }}</h6>
                        <p class="fw-bold mb-0" style="color: #02767F; font-size: 1.1rem;">
                            {{ number_format($item->sourceable->product_price, 0) }} {{ __('EGP') }}
                        </p>
                    </div>
                </div>
                @if($item->action_link)
                <button onclick="window.location.href='{{ $item->action_link }}'" class="btn-teal mt-3">
                    {{ __('Buy Now') }}
                </button>
                @endif
            </div>
            @elseif($item->type == 'job' && $item->sourceable)
            <div class="mx-3 mb-3 p-3 rounded-3" style="background: #eff6ff; border: 1px solid #bfdbfe;">
                <h6 class="fw-bold mb-2" style="color: #1e40af;">{{ $item->sourceable->job_title }}</h6>
                <small class="d-block mb-1"><i class="bi bi-geo-alt"></i> {{ $item->sourceable->job_location }}</small>
                @if($item->action_link)
                <button onclick="window.location.href='{{ $item->action_link }}'" class="btn-teal mt-2" style="background: #2563eb;">
                    {{ __('Apply Now') }}
                </button>
                @endif
            </div>
            @elseif($item->media_url)
            <img src="{{ asset('storage/' . $item->media_url) }}" class="img-fluid" alt="Post media">
            @endif

            {{-- Video Player --}}
            @if($item->video_url)
            <div class="video-container position-relative" style="background: #000;">
                @if($item->video_provider == 'upload')
                    {{-- Uploaded Video --}}
                    <video class="w-100" controls playsinline preload="metadata" poster="{{ $item->video_thumbnail ? asset('storage/' . $item->video_thumbnail) : '' }}">
                        <source src="{{ asset('storage/' . $item->video_url) }}" type="video/mp4">
                        {{ __('Your browser does not support the video tag.') }}
                    </video>
                @elseif($item->video_provider == 'youtube')
                    {{-- YouTube Embed --}}
                    <div class="ratio ratio-16x9">
                        <iframe src="{{ $item->video_url }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                @elseif($item->video_provider == 'vimeo')
                    {{-- Vimeo Embed --}}
                    <div class="ratio ratio-16x9">
                        <iframe src="{{ $item->video_url }}" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
                    </div>
                @endif
            </div>
            @endif

            {{-- Actions --}}
            <div class="d-flex justify-content-between align-items-center px-3 py-2 border-top">
                <div class="d-flex gap-3">
                    <form action="{{ route('feed.like.' . app()->getLocale(), $item->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-link text-decoration-none p-0" style="color: {{ $item->liked_by_user ? '#ef4444' : '#6b7280' }};">
                            <span style="font-size: 20px;">{{ $item->liked_by_user ? '❤️' : '🤍' }}</span>
                            <small class="ms-1">{{ $item->likes_count ?? 0 }}</small>
                        </button>
                    </form>
                    <button class="btn btn-sm btn-link text-muted text-decoration-none p-0" onclick="toggleCommentForm('{{ $item->id }}')">
                        <span style="font-size: 20px;">💬</span>
                        <small class="ms-1">{{ $item->comments_count ?? 0 }}</small>
                    </button>
                </div>
            </div>

            {{-- Comments Section --}}
            @include('web.feed.partials.comments-section', ['feedItem' => $item])
        </div>
        @empty
        <div class="text-center py-5">
            <div style="font-size: 4rem;">📭</div>
            <h5 class="mt-3">{{ __('No posts yet') }}</h5>
            <p class="text-muted">{{ __('Be the first to share something!') }}</p>
        </div>
        @endforelse
    </div>
</div>

{{-- Bottom Navigation --}}
<div class="bottom-nav">
    <a href="{{ route('web.profile.index.' . app()->getLocale()) }}" class="nav-item">
        <div class="icon">👤</div>
        <span>{{ __('Profile') }}</span>
    </a>
    <a href="{{ route('web.jobs.index.' . app()->getLocale()) }}" class="nav-item">
        <div class="icon">💼</div>
        <span>{{ __('Jobs') }}</span>
    </a>
    <button onclick="openCreateModal()" class="nav-item border-0 bg-transparent">
        <div class="create-btn">➕</div>
        <span>{{ __('Create') }}</span>
    </button>
    <a href="{{ route('web.shop.show.' . app()->getLocale()) }}" class="nav-item">
        <div class="icon">🛍️</div>
        <span>{{ __('Shop') }}</span>
    </a>
    <a href="{{ route('feed.index.' . app()->getLocale()) }}" class="nav-item active">
        <div class="icon">🏠</div>
        <span>{{ __('Home') }}</span>
    </a>
</div>

{{-- Create Modal --}}
<div id="createModal" class="modal fade" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header border-0">
                <h5 class="modal-title">{{ __('Create New Post') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('feed.store.' . app()->getLocale()) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <textarea name="description" class="form-control border-0 bg-light" rows="4" 
                                  placeholder="{{ __('Share your thoughts...') }}" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-medium">{{ __('Add Media') }}</label>
                        <div class="btn-group w-100 mb-2" role="group">
                            <input type="radio" class="btn-check" name="media_type" id="media_type_image" value="image" checked>
                            <label class="btn btn-outline-secondary btn-sm" for="media_type_image">📷 {{ __('Image') }}</label>
                            
                            <input type="radio" class="btn-check" name="media_type" id="media_type_video" value="video">
                            <label class="btn btn-outline-secondary btn-sm" for="media_type_video">🎥 {{ __('Video') }}</label>
                            
                            <input type="radio" class="btn-check" name="media_type" id="media_type_link" value="link">
                            <label class="btn btn-outline-secondary btn-sm" for="media_type_link">🔗 {{ __('Link') }}</label>
                        </div>
                        
                        <div id="image_upload" class="media-upload-section">
                            <input type="file" name="media" accept="image/*" class="form-control">
                            <small class="text-muted">{{ __('Max size: 5MB') }}</small>
                        </div>
                        
                        <div id="video_upload" class="media-upload-section" style="display: none;">
                            <input type="file" name="video" accept="video/mp4,video/webm,video/mov,video/avi" class="form-control">
                            <small class="text-muted">{{ __('Formats: MP4, WebM, MOV, AVI. Max size: 100MB') }}</small>
                        </div>
                        
                        <div id="video_link" class="media-upload-section" style="display: none;">
                            <input type="url" name="video_url" class="form-control" placeholder="https://youtube.com/watch?v=... or https://vimeo.com/...">
                            <small class="text-muted">{{ __('Paste YouTube or Vimeo link') }}</small>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn-teal">{{ __('Post Now') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function openCreateModal() {
    const modal = new bootstrap.Modal(document.getElementById('createModal'));
    modal.show();
}

// Handle media type switching
document.addEventListener('DOMContentLoaded', function() {
    const mediaTypeRadios = document.querySelectorAll('input[name="media_type"]');
    const imageSect = document.getElementById('image_upload');
    const videoSect = document.getElementById('video_upload');
    const linkSect = document.getElementById('video_link');
    
    mediaTypeRadios.forEach(radio => {
        radio.addEventListener('change', function() {
            imageSect.style.display = 'none';
            videoSect.style.display = 'none';
            linkSect.style.display = 'none';
            
            if (this.value === 'image') imageSect.style.display = 'block';
            else if (this.value === 'video') videoSect.style.display = 'block';
            else if (this.value === 'link') linkSect.style.display = 'block';
        });
    });
});
</script>
@endsection
