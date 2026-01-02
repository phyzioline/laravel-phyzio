<div class="card shadow-sm border-0 mb-4 feed-card" data-id="{{ $item->id }}">
    <div class="card-header bg-white border-0 d-flex align-items-center pt-3 pb-0">
        <div class="icon-box rounded-circle d-flex align-items-center justify-content-center text-white mr-3 shadow-sm"
             style="width: 45px; height: 45px; background: #ff9800;">
            <i class="las la-briefcase la-lg"></i>
        </div>
        <div>
            <h6 class="mb-0 font-weight-bold" style="color: #333;">{{ __('New Job Opportunity') }}</h6>
            <small class="text-muted">{{ $item->created_at->diffForHumans() }}</small>
        </div>
    </div>

    <div class="card-body">
        <div class="d-flex align-items-start mb-3">
            @if($item->media_url)
                <img src="{{ $item->media_url }}" class="rounded shadow-sm mr-3" style="width: 60px; height: 60px; object-fit: cover;" alt="Company Logo">
            @endif
            <div>
                <h5 class="card-title font-weight-bold text-dark mb-1">
                    {{ $isArabic ? ($item->sourceable->title_ar ?? $item->title) : ($item->sourceable->title_en ?? $item->title) }}
                </h5>
                <p class="text-muted mb-0 small">
                    <i class="las la-building"></i> {{ $item->sourceable->company_name ?? 'Company' }}
                    <span class="mx-2">•</span>
                    <i class="las la-map-marker"></i> {{ $item->sourceable->location ?? 'Location' }}
                </p>
            </div>
        </div>
        
        <div class="bg-light p-3 rounded mb-3">
            <p class="card-text text-dark mb-0">
                {{ Str::limit($isArabic ? ($item->sourceable->description_ar ?? $item->description) : ($item->sourceable->description_en ?? $item->description), 200) }}
            </p>
        </div>
    </div>

    <div class="card-footer bg-white border-top-0 pt-0 pb-3">
         <a href="{{ route('web.jobs.show.' . app()->getLocale(), $item->sourceable_id) }}" class="btn btn-block btn-warning text-white shadow-sm font-weight-bold py-2 mb-3">
            {{ __('Apply Now') }} <i class="las la-arrow-right ml-2"></i>
         </a>
         
         @include('web.feed.partials.interactions')
    </div>
</div>
