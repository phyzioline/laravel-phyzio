<div class="card shadow-sm border-0 mb-4 feed-card" data-id="{{ $item->id }}">
    <div class="card-header bg-white border-0 d-flex align-items-center pt-3 pb-0">
        <div class="icon-box rounded-circle d-flex align-items-center justify-content-center text-white mr-3 shadow-sm"
             style="width: 45px; height: 45px; background: #28a745;">
            <i class="las la-shopping-bag la-lg"></i>
        </div>
        <div>
            <h6 class="mb-0 font-weight-bold" style="color: #333;">{{ __('New Product') }}</h6>
            <small class="text-muted">{{ $item->created_at->diffForHumans() }}</small>
        </div>
    </div>

    <div class="card-body">
        <h5 class="card-title font-weight-bold text-dark mb-2">
            {{ $isArabic ? ($item->sourceable->product_name_ar ?? $item->title) : ($item->sourceable->product_name_en ?? $item->title) }}
        </h5>
        <p class="card-text text-muted mb-3">
            {{ Str::limit($isArabic ? ($item->sourceable->product_desc_ar ?? $item->description) : ($item->sourceable->product_desc_en ?? $item->description), 150) }}
        </p>
        
        @if($item->media_url)
        <div class="rounded overflow-hidden mb-3 shadow-sm position-relative">
             <img src="{{ $item->media_url }}" class="img-fluid w-100" style="object-fit: contain; max-height: 400px; background: #f8f9fa;" alt="Product Image">
             <div class="position-absolute bg-white px-3 py-1 rounded-pill shadow-sm font-weight-bold text-success" style="bottom: 15px; right: 15px;">
                {{ $item->sourceable->product_price ?? 'N/A' }} EGP
             </div>
        </div>
        @endif
    </div>

    <div class="card-footer bg-white border-top-0 pt-0 pb-3">
         <a href="{{ route('product.show.' . app()->getLocale(), $item->sourceable_id) }}" class="btn btn-block btn-success shadow-sm font-weight-bold py-2 mb-3">
            {{ __('Buy Now') }} <i class="las la-shopping-cart ml-2"></i>
         </a>
         
         @include('web.feed.partials.interactions')
    </div>
</div>
