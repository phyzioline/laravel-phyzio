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
<div class="bg-light min-vh-100 py-4">
    <div class="container">
        <div class="row justify-content-center">
            
            <!-- Left Sidebar filters -->
            <div class="col-md-3 d-none d-lg-block">
                <div class="card shadow-sm border-0 sticky-top" style="top: 80px;">
                    <div class="card-body">
                        <h6 class="text-uppercase text-muted mb-3 font-weight-bold" style="font-size: 12px;">{{ __('FILTERS') }}</h6>
                        
                        <div class="nav flex-column nav-pills">
                             <a href="#" class="nav-link active rounded-pill mb-2">
                                <i class="las la-globe mr-2"></i> {{ __('All Updates') }}
                             </a>
                             <a href="#" class="nav-link rounded-pill mb-2 text-dark">
                                <i class="las la-graduation-cap mr-2 text-primary"></i> {{ __('Courses') }}
                             </a>
                             <a href="#" class="nav-link rounded-pill mb-2 text-dark">
                                <i class="las la-shopping-cart mr-2 text-success"></i> {{ __('New Products') }}
                             </a>
                             <a href="#" class="nav-link rounded-pill mb-2 text-dark">
                                <i class="las la-briefcase mr-2 text-warning"></i> {{ __('Jobs') }}
                             </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Feed -->
            <div class="col-md-7">

                <!-- Create Post Card -->
                 <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body">
                        <div class="d-flex">
                            <div class="icon-box rounded-circle d-flex align-items-center justify-content-center bg-light text-primary mr-3" style="width: 45px; height: 45px;">
                                <i class="las la-user-edit la-lg"></i>
                            </div>
                            <div class="w-100">
                                <form action="{{ route('feed.store.' . app()->getLocale()) }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group mb-2">
                                        <textarea name="content" class="form-control border-0 bg-light" rows="2" placeholder="{{ __('Share your medical insights, cases, or questions...') }}" style="resize: none;"></textarea>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <label class="btn btn-sm btn-light mb-0 text-muted" style="cursor: pointer;">
                                                <i class="las la-image"></i> Photo
                                                <input type="file" name="image" class="d-none">
                                            </label>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm px-4">Post</button>
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
                    <div class="text-center py-5">
                        <div class="mb-3"><i class="las la-stream display-4 text-muted"></i></div>
                        <h4>{{ __('Your feed is empty') }}</h4>
                        <p class="text-muted">{{ __('Follow more topics or wait for admin updates.') }}</p>
                    </div>
                @endforelse

                <!-- Pagination -->
                <div class="d-flex justify-content-center">
                    {{ $feedItems->links() }}
                </div>
            </div>
            
            <!-- Right Sidebar (Recommended) -->
             <div class="col-md-2 d-none d-xl-block">
                 <div class="card border-0 bg-transparent">
                     <h6 class="text-muted mb-3 font-weight-bold" style="font-size: 11px; letter-spacing: 1px;">SUGGESTED FOR YOU</h6>
                     <!-- Dummy Suggestions -->
                     <ul class="list-group list-group-flush bg-transparent">
                         <li class="list-group-item bg-transparent px-0 border-0">
                             <div class="d-flex align-items-center">
                                 <div class="bg-white p-2 rounded shadow-sm mr-2"><i class="las la-stethoscope text-primary"></i></div>
                                 <div style="line-height: 1.2;">
                                     <a href="#" class="text-dark font-weight-bold" style="font-size: 12px;">Manual Therapy Masterclass</a>
                                 </div>
                             </div>
                         </li>
                     </ul>
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
