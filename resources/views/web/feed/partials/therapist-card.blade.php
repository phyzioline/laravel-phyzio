<div class="card shadow-sm border-0 mb-4 feed-card" data-id="{{ $item->id }}">
    <div class="card-header bg-white border-0 d-flex align-items-center pt-3 pb-0">
        <div class="icon-box rounded-circle d-flex align-items-center justify-content-center text-white mr-3 shadow-sm"
             style="width: 45px; height: 45px; background: #673ab7;">
            <i class="las la-user-nurse la-lg"></i>
        </div>
        <div>
            <h6 class="mb-0 font-weight-bold" style="color: #333;">{{ __('New Professional') }}</h6>
            <small class="text-muted">{{ $item->created_at->diffForHumans() }}</small>
        </div>
    </div>

    <div class="card-body">
        <div class="d-flex align-items-center mb-3">
             <div class="mr-3">
                 @if($item->sourceable && $item->sourceable->profile_photo_path)
                    <img src="{{ asset('storage/'.$item->sourceable->profile_photo_path) }}" class="rounded-circle shadow-sm" style="width: 70px; height: 70px; object-fit: cover;">
                 @else
                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center shadow-sm" style="width: 70px; height: 70px;">
                        <i class="las la-user la-2x text-muted"></i>
                    </div>
                 @endif
             </div>
             <div>
                <h5 class="card-title font-weight-bold text-dark mb-1">{{ $item->sourceable->name ?? 'Therapist' }}</h5>
                <p class="text-primary mb-0 font-weight-bold">{{ $item->sourceable->therapistProfile->title ?? 'Specialist' }}</p>
                <p class="text-muted small mb-0"><i class="las la-map-marker"></i> {{ $item->sourceable->therapistProfile->city ?? 'Location' }}</p>
             </div>
        </div>

        <p class="card-text text-muted mb-3">
            {{ Str::limit($item->sourceable->therapistProfile->bio ?? __('I am now available for home visits and consultations.'), 150) }}
        </p>
        
        <div class="d-flex flex-wrap gap-2 mb-3">
            <!-- Example Badges -->
            <span class="badge badge-light border text-primary px-3 py-2 rounded-pill"><i class="las la-home"></i> {{ __('Home Visits') }}</span>
            <span class="badge badge-light border text-success px-3 py-2 rounded-pill"><i class="las la-check-circle"></i> {{ __('Verified') }}</span>
        </div>
    </div>

    <div class="card-footer bg-white border-top-0 pt-0 pb-3">
         <div class="row">
             <div class="col-6">
                 <a href="{{ route('web.home_visits.show.' . app()->getLocale(), $item->sourceable_id) }}" class="btn btn-outline-primary btn-block rounded-pill font-weight-bold">
                    {{ __('View Profile') }}
                 </a>
             </div>
             <div class="col-6">
                 <a href="{{ route('web.home_visits.book.' . app()->getLocale(), $item->sourceable_id) }}" class="btn btn-primary btn-block rounded-pill font-weight-bold shadow-sm">
                    {{ __('Book Now') }}
                 </a>
             </div>
         </div>
         
         @include('web.feed.partials.interactions')
    </div>
</div>
