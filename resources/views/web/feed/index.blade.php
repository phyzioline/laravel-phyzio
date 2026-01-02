@extends('web.layouts.app')

@php
    $isArabic = app()->getLocale() === 'ar';
    $pageMeta = \App\Services\SEO\SEOService::getPageMeta('feed');
@endphp

@section('title', $pageMeta['title'])

@push('meta')
    <meta name="description" content="{{ $pageMeta['description'] }}">
    <meta name="keywords" content="{{ $pageMeta['keywords'] }}">
    <meta property="og:title" content="{{ $pageMeta['title'] }}">
    <meta property="og:description" content="{{ $pageMeta['description'] }}">
    <meta property="og:type" content="website">
@endpush

@section('content')
<div class="bg-light min-vh-100" style="padding-top: 120px; padding-bottom: 2rem;">
    <div class="container">
        <div class="row justify-content-center">
            
            <!-- Left Sidebar filters -->
            <div class="col-md-3 d-none d-lg-block">
                <div class="card shadow-sm border-0 sticky-top" style="top: 90px; border-radius: 12px;">
                    <div class="card-body p-3">
                        <div class="d-flex align-items-center mb-4 px-2">
                             <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mr-3" style="width: 48px; height: 48px;">
                                <i class="las la-stream text-primary" style="font-size: 24px;"></i>
                             </div>
                             <div>
                                 <h6 class="mb-0 font-weight-bold text-dark">{{ __('Your Feed') }}</h6>
                                 <small class="text-muted">{{ __('Professional Network') }}</small>
                             </div>
                        </div>
                        
                        <div class="nav flex-column nav-pills" id="feed-filters">
                             <a href="{{ route('feed.index.' . app()->getLocale()) }}" 
                                class="nav-link mb-1 {{ !request('type') ? 'active-teal font-weight-bold' : 'text-muted' }}">
                                <i class="las la-globe {{ !request('type') ? '' : 'text-teal' }}"></i> {{ __('All Updates') }}
                             </a>
                             
                             <a href="{{ route('feed.index.' . app()->getLocale(), ['type' => 'my_posts']) }}" 
                                class="nav-link mb-1 {{ request('type') == 'my_posts' ? 'active-teal font-weight-bold' : 'text-muted' }}">
                                <i class="las la-user {{ request('type') == 'my_posts' ? '' : 'text-teal' }}"></i> {{ __('My Posts') }}
                             </a>

                             <a href="{{ route('feed.index.' . app()->getLocale(), ['type' => 'course']) }}" 
                                class="nav-link mb-1 {{ request('type') == 'course' ? 'active-teal font-weight-bold' : 'text-muted' }}">
                                <i class="las la-graduation-cap {{ request('type') == 'course' ? '' : 'text-teal' }}"></i> {{ __('Courses') }}
                             </a>
                             <a href="{{ route('feed.index.' . app()->getLocale(), ['type' => 'product']) }}" 
                                class="nav-link mb-1 {{ request('type') == 'product' ? 'active-teal font-weight-bold' : 'text-muted' }}">
                                <i class="las la-shopping-cart {{ request('type') == 'product' ? '' : 'text-teal' }}"></i> {{ __('Products') }}
                             </a>
                             <a href="{{ route('feed.index.' . app()->getLocale(), ['type' => 'job']) }}" 
                                class="nav-link mb-1 {{ request('type') == 'job' ? 'active-teal font-weight-bold' : 'text-muted' }}">
                                <i class="las la-briefcase {{ request('type') == 'job' ? '' : 'text-teal' }}"></i> {{ __('Jobs') }}
                             </a>
                             <a href="{{ route('feed.index.' . app()->getLocale(), ['type' => 'therapist']) }}" 
                                class="nav-link mb-1 {{ request('type') == 'therapist' ? 'active-teal font-weight-bold' : 'text-muted' }}">
                                <i class="las la-user-nurse {{ request('type') == 'therapist' ? '' : 'text-teal' }}"></i> {{ __('Experts') }}
                             </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Feed -->
            <div class="col-md-7 col-lg-6">

                <!-- Create Post Card -->
                 <div class="card shadow-sm border-0 mb-4 feed-card">
                    <div class="card-body p-3">
                        <div class="d-flex">
                            <div class="icon-box rounded-circle d-flex align-items-center justify-content-center bg-light text-primary mr-3" style="width: 45px; height: 45px;">
                                @if(auth()->user()->profile_photo_url)
                                    <img src="{{ auth()->user()->profile_photo_url }}" class="rounded-circle w-100 h-100 shadow-sm" style="object-fit:cover">
                                @else
                                    <i class="las la-user-circle la-2x text-muted"></i>
                                @endif
                            </div>
                            <div class="w-100">
                                <form action="{{ route('feed.store.' . app()->getLocale()) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group mb-2 position-relative">
                                        <textarea name="content" class="form-control border-0 bg-light rounded-lg px-3 py-2" rows="2" placeholder="{{ __('Share your medical insights, cases, or questions...') }}" style="resize: none; font-size: 15px;"></textarea>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                                        <div class="d-flex">
                                            <label class="btn btn-sm btn-link text-decoration-none text-muted mb-0 mr-2 p-0 d-flex align-items-center" style="cursor: pointer;">
                                                <i class="las la-image text-success font-size-18 mr-1"></i> <span class="small font-weight-bold">{{ __('Photo') }}</span>
                                                <input type="file" name="image" class="d-none">
                                            </label>
                                            <label class="btn btn-sm btn-link text-decoration-none text-muted mb-0 p-0 d-flex align-items-center" style="cursor: pointer;">
                                                <i class="las la-video text-danger font-size-18 mr-1"></i> <span class="small font-weight-bold">{{ __('Video') }}</span>
                                                <input type="file" name="video" class="d-none" accept="video/*">
                                            </label>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm px-4 rounded-pill font-weight-bold shadow-sm">{{ __('Publish Post') }}</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                
                @forelse($feedItems as $item)
                    @if($item->type == 'product')
                        @include('web.feed.partials.product-card', ['item' => $item])
                    @elseif($item->type == 'job')
                        @include('web.feed.partials.job-card', ['item' => $item])
                    @elseif($item->type == 'course')
                        @include('web.feed.partials.course-card', ['item' => $item])
                    @elseif($item->type == 'therapist')
                        @include('web.feed.partials.therapist-card', ['item' => $item])
                    @else
                        @include('web.feed.partials.post-card', ['item' => $item])
                    @endif
                @empty
                    <!-- Premium Empty State / Discovery Board -->
                    <div class="empty-feed-hero mb-4">
                        <div class="mb-4">
                            <img src="{{ asset('assets/images/illustrations/feed_empty.svg') }}" onerror="this.src='https://cdn-icons-png.flaticon.com/512/7486/7486744.png'" style="width: 120px; opacity: 0.8;" alt="Feed Empty">
                        </div>
                        <h4 class="font-weight-bold text-dark mb-2">{{ __('Welcome to your Professional Feed') }}</h4>
                        <p class="text-muted mb-4 px-md-5">{{ __('Your customized stream of medical updates, career opportunities, and latest products starts here. Discover what is happening now.') }}</p>
                        
                        <div class="row px-2">
                             <!-- Products Discovery -->
                             <div class="col-6 mb-3">
                                 <a href="{{ route('web.shop.show.' . app()->getLocale()) }}" class="text-decoration-none discovery-card">
                                     <div class="card border-0 bg-light h-100 p-3 text-center hover-lift">
                                         <div class="discovery-icon bg-white text-success shadow-sm mx-auto">
                                             <i class="las la-shopping-cart"></i>
                                         </div>
                                         <h6 class="text-dark font-weight-bold mb-1">{{ __('Shop') }}</h6>
                                         <small class="text-muted">{{ __('Latest Equipment') }}</small>
                                     </div>
                                 </a>
                             </div>
                             <!-- Courses Discovery -->
                             <div class="col-6 mb-3">
                                 <a href="{{ route('web.courses.index') }}" class="text-decoration-none discovery-card">
                                     <div class="card border-0 bg-light h-100 p-3 text-center hover-lift">
                                         <div class="discovery-icon bg-white text-primary shadow-sm mx-auto">
                                             <i class="las la-graduation-cap"></i>
                                         </div>
                                         <h6 class="text-dark font-weight-bold mb-1">{{ __('Learn') }}</h6>
                                         <small class="text-muted">{{ __('New Courses') }}</small>
                                     </div>
                                 </a>
                             </div>
                             <!-- Jobs Discovery -->
                             <div class="col-6 mb-3">
                                 <a href="{{ route('web.jobs.index.' . app()->getLocale()) }}" class="text-decoration-none discovery-card">
                                     <div class="card border-0 bg-light h-100 p-3 text-center hover-lift">
                                         <div class="discovery-icon bg-white text-warning shadow-sm mx-auto">
                                             <i class="las la-briefcase"></i>
                                         </div>
                                         <h6 class="text-dark font-weight-bold mb-1">{{ __('Jobs') }}</h6>
                                         <small class="text-muted">{{ __('Career Moves') }}</small>
                                     </div>
                                 </a>
                             </div>
                             <!-- Experts Discovery -->
                             <div class="col-6 mb-3">
                                 <a href="{{ route('web.home_visits.index.' . app()->getLocale()) }}" class="text-decoration-none discovery-card">
                                     <div class="card border-0 bg-light h-100 p-3 text-center hover-lift">
                                         <div class="discovery-icon bg-white text-info shadow-sm mx-auto">
                                             <i class="las la-user-nurse"></i>
                                         </div>
                                         <h6 class="text-dark font-weight-bold mb-1">{{ __('Experts') }}</h6>
                                         <small class="text-muted">{{ __('Connect Now') }}</small>
                                     </div>
                                 </a>
                             </div>
                        </div>
                    </div>
                @endforelse

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $feedItems->links() }}
                </div>
            </div>
            
            <!-- Right Sidebar (Recommended) -->
             <div class="col-md-3 d-none d-xl-block">
                 <div class="card border-0 shadow-sm sticky-top" style="top: 90px; border-radius: 12px;">
                     <div class="card-header bg-white border-0 pb-0 pt-3">
                        <h6 class="text-dark font-weight-bold mb-0" style="font-size: 14px;">{{ __('Trending Now') }} <i class="las la-fire text-danger ml-1"></i></h6>
                     </div>
                     <div class="card-body px-0 pt-2">
                         <ul class="list-group list-group-flush">
                             <li class="list-group-item border-0 py-2 px-3 action-item">
                                 <div class="d-flex align-items-center">
                                     <div class="bg-light rounded p-2 mr-3 text-center" style="width: 40px;"><i class="las la-hashtag"></i></div>
                                     <div style="line-height:1.2">
                                         <span class="d-block font-weight-bold text-dark" style="font-size: 13px;">#PhyziolineConf2026</span>
                                         <small class="text-muted">2.4k posts</small>
                                     </div>
                                 </div>
                             </li>
                             <li class="list-group-item border-0 py-2 px-3 action-item">
                                 <div class="d-flex align-items-center">
                                     <div class="bg-light rounded p-2 mr-3 text-center" style="width: 40px;"><i class="las la-hashtag"></i></div>
                                     <div style="line-height:1.2">
                                         <span class="d-block font-weight-bold text-dark" style="font-size: 13px;">#ManualTherapy</span>
                                         <small class="text-muted">850 posts</small>
                                     </div>
                                 </div>
                             </li>
                         </ul>
                     </div>
                     <div class="card-footer bg-white border-0 text-center pb-3 pt-0">
                         <a href="#" class="btn btn-sm btn-light btn-block rounded-pill text-primary font-weight-bold">{{ __('View All') }}</a>
                     </div>
                 </div>
                 
                 <!-- Footer Links -->
                 <div class="mt-4 text-center px-3">
                     <p class="small text-muted mb-2">
                         <a href="#" class="text-muted mr-2">Privacy</a>
                         <a href="#" class="text-muted mr-2">Terms</a>
                         <a href="#" class="text-muted">Advertising</a>
                     </p>
                     <p class="small text-muted">© 2026 Phyzioline Inc.</p>
                 </div>
             </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    function toggleLike(id) {
        $.post('{{ route("feed.like." . app()->getLocale(), ":id") }}'.replace(':id', id), {
            _token: '{{ csrf_token() }}'
        })
        .done(function(data) {
            let btn = $('.feed-card[data-id="'+id+'"] .like-btn');
            let countSpan = btn.find('.like-count');
            let currentCount = parseInt(countSpan.text());
            
            if(data.liked) {
                btn.addClass('text-primary');
                countSpan.text(currentCount + 1);
            } else {
                btn.removeClass('text-primary');
                countSpan.text(currentCount - 1);
            }
        });
    }

    // Log view on scroll logic (Simple version)
    // In production, use IntersectionObserver to log 'view' when 50% visible
</script>
@endpush
@endsection
