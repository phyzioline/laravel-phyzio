<div class="card shadow-sm border-0 mb-4 feed-card" data-id="{{ $item->id }}">
    <div class="card-header bg-white border-0 d-flex align-items-center pt-3 pb-0">
        <div class="icon-box rounded-circle d-flex align-items-center justify-content-center text-primary bg-light mr-3"
             style="width: 45px; height: 45px;">
             @if($item->sourceable && $item->sourceable->profile_photo_path)
                <img src="{{ asset('storage/'.$item->sourceable->profile_photo_path) }}" class="rounded-circle w-100 h-100" style="object-fit: cover;">
             @else
                <i class="las la-user la-lg"></i>
             @endif
        </div>
        <div>
            <h6 class="mb-0 font-weight-bold" style="color: #333;">{{ $item->sourceable->name ?? 'User' }}</h6>
            <small class="text-muted">
                {{ $item->sourceable->type ?? 'Professional' }} • {{ $item->created_at->diffForHumans() }}
            </small>
        </div>
        <div class="ml-auto">
            <button class="btn btn-sm btn-link text-muted"><i class="las la-ellipsis-h"></i></button>
        </div>
    </div>

    <div class="card-body">
        <p class="card-text text-dark mb-3" style="font-size: 15px; line-height: 1.6;">
            {!! nl2br(e($item->description)) !!}
        </p>
        
        @if($item->media_url)
        <div class="rounded overflow-hidden mb-3 shadow-sm">
             <img src="{{ $item->media_url }}" class="img-fluid w-100" style="object-fit: cover; max-height: 500px;" alt="Post Image">
        </div>
        @endif
    </div>

    <div class="card-footer bg-white border-top-0 pt-0 pb-3">
         @include('web.feed.partials.interactions')
    </div>
</div>
