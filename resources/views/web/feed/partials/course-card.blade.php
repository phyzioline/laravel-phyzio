<div class="card shadow-sm border-0 mb-4 feed-card" data-id="{{ $item->id }}">
    <div class="card-header bg-white border-0 d-flex align-items-center pt-3 pb-0">
        <div class="icon-box rounded-circle d-flex align-items-center justify-content-center text-white mr-3 shadow-sm"
             style="width: 45px; height: 45px; background: #00897b;">
            <i class="las la-graduation-cap la-lg"></i>
        </div>
        <div>
            <h6 class="mb-0 font-weight-bold" style="color: #333;">{{ __('New Course') }}</h6>
            <small class="text-muted">{{ $item->created_at->diffForHumans() }}</small>
        </div>
    </div>

    <div class="card-body">
        <h5 class="card-title font-weight-bold text-dark mb-2">
            {{ $isArabic ? ($item->sourceable->title_ar ?? $item->title) : ($item->sourceable->title_en ?? $item->title) }}
        </h5>
        
        @if($item->media_url)
        <div class="rounded overflow-hidden mb-3 shadow-sm position-relative">
             <img src="{{ $item->media_url }}" class="img-fluid w-100" style="object-fit: cover; max-height: 400px;" alt="Course Thumbnail">
             <div class="position-absolute bg-primary text-white px-3 py-1 rounded-pill" style="top: 15px; right: 15px; font-size: 12px; opacity: 0.9;">
                {{ __('Enrolling Now') }}
             </div>
             <div class="position-absolute bg-dark text-white px-2 py-1 rounded" style="bottom: 15px; right: 15px; font-size: 11px; opacity: 0.8;">
                <i class="las la-clock"></i> {{ $item->sourceable->duration ?? 'Duration' }}
             </div>
        </div>
        @endif

        <p class="card-text text-muted mb-3">
            {{ Str::limit($isArabic ? ($item->sourceable->short_description_ar ?? $item->description) : ($item->sourceable->short_description_en ?? $item->description), 150) }}
        </p>
    </div>

    <div class="card-footer bg-white border-top-0 pt-0 pb-3">
         <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0 font-weight-bold text-primary">
                {{ $item->sourceable->price ?? 'Free' }} {{ __('EGP') }}
            </h5>
             <a href="{{ route('web.courses.show.' . app()->getLocale(), $item->sourceable_id) }}" class="btn btn-primary shadow-sm font-weight-bold px-4">
                {{ __('Enroll Now') }}
             </a>
         </div>
         
         @include('web.feed.partials.interactions')
    </div>
</div>
